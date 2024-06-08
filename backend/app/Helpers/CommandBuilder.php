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
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull(true, $backupName, $backupManagerWorkDir, $backupConnectionConfig, $backupDriverConfig);
        $command .= ' && '.CommandBuilder::push(true, $backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig);

        return $command;
    }

    public static function restore(
        string $backupName,
        ConnectionConfig $backupConnectionConfig,
        BackupDriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig,
    ) {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::pull(false, $backupName, $backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig);

        $command .= ' && '.CommandBuilder::push(false, $backupName, $backupManagerWorkDir, $backupConnectionConfig, $backupDriverConfig);

        return $command;
    }

    public static function delete(
        string $backupName,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig
    ) {
        $command = CommandBuilder::execute(
            $storageServerDriverConfig->driver->delete($backupName),
            $storageServerConnectionConfig
        );

        return $command;
    }
}
