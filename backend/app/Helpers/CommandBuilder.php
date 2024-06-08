<?php

namespace App\Helpers;

use App\Entities\BackupDriverConfig;
use App\Entities\ConnectionConfig;
use App\Entities\StorageServerDriverConfig;
use Illuminate\Support\Str;

class CommandBuilder
{
    public static function push(
        bool $isBackup,
        string $backupName,
        string $backupManagerWorkDir,
        ConnectionConfig $connectionConfig,
        BackupDriverConfig|StorageServerDriverConfig $driverConfig,
        $layers = []
    ) {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext(true);
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext(true);
        }

        $command = '';

        foreach ($layers as $layer) {
            $command .= $layer->setup().' && ';
            if ($isBackup) {
                $command .= $layer->apply($localWorkDir).' && ';
            } else {
                $command .= $layer->unapply($localWorkDir).' && ';
            }
            $command .= $layer->clean().' && ';
        }

        $command .= $driver->setup();
        if ($isBackup) {
            $command .= ' && '.$driver->push($localWorkDir, $backupName);
        } else {
            $command .= ' && '.$driver->push($localWorkDir);
        }
        $command .= ' && '.$driver->clean();

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->setup().' && '.$connection->push($localWorkDir, $externalWorkDir).' && '.$connection->run($command).' && '.$connection->clean();
        }

        return $command;
    }

    public static function pull(
        bool $isBackup,
        string $backupName,
        string $backupManagerWorkDir,
        ConnectionConfig $connectionConfig,
        BackupDriverConfig|StorageServerDriverConfig $driverConfig,
        array $layers = []
    ) {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext(true);
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext(true);
        }

        $command = $driver->setup();
        if ($isBackup) {
            $command .= ' && '.$driver->pull($localWorkDir);
        } else {
            $command .= ' && '.$driver->pull($localWorkDir, $backupName);
        }
        $command .= ' && '.$driver->clean();

        foreach ($layers as $layer) {
            $command .= ' && '.$layer->setup();
            if ($isBackup) {
                $command .= $layer->apply($localWorkDir).' && ';
            } else {
                $command .= $layer->unapply($localWorkDir).' && ';
            }
            $command .= ' && '.$layer->clean();
        }

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->setup().' && '.$connection->run($command).' && '.$connection->pull($localWorkDir, $externalWorkDir).' && '.$connection->clean();
        }

        return $command;
    }

    public static function execute(string $command, ConnectionConfig $connectionConfig)
    {
        $connections = $connectionConfig->connections;

        if (count($connections)) {
            $connections[0]->dockerContext(true);
        }

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->setup().' && '.$connection->run($command).' && '.$connection->clean();
        }

        return $command;
    }

    public static function backup(
        string $backupName,
        ConnectionConfig $backupConnectionConfig,
        BackupDriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
        array $backupLayers = [],
        array $backupManagerLayers = [],
        array $storageServerLayers = []
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull(true, $backupName, $backupManagerWorkDir, $backupConnectionConfig, $backupDriverConfig, $backupLayers).' && ';

        foreach ($backupManagerLayers as $backupManagerLayer) {
            $command .= $backupManagerLayer->setup().' && ';
            $command .= $backupManagerLayer->apply($backupManagerWorkDir).' && ';
            $command .= $backupManagerLayer->clean().' && ';
        }

        $command .= CommandBuilder::push(true, $backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig, $storageServerLayers);

        return $command;
    }

    public static function restore(
        string $backupName,
        ConnectionConfig $backupConnectionConfig,
        BackupDriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
        array $backupLayers = [],
        array $backupManagerLayers = [],
        array $storageServerLayers = []
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull(false, $backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig, $storageServerLayers).' && ';

        foreach ($backupManagerLayers as $backupManagerLayer) {
            $command .= $backupManagerLayer->setup().' && ';
            $command .= $backupManagerLayer->unapply($backupManagerWorkDir).' && ';
            $command .= $backupManagerLayer->clean().' && ';
        }

        $command .= CommandBuilder::push(false, $backupName, $backupManagerWorkDir, $backupConnectionConfig, $backupDriverConfig, $backupLayers);

        return $command;
    }
}
