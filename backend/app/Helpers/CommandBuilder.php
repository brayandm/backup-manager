<?php

namespace App\Helpers;

use App\Entities\BackupDriverConfig;
use App\Entities\ConnectionConfig;
use App\Entities\StorageServerDriverConfig;
use Illuminate\Support\Str;

class CommandBuilder
{
    public static function Push(string $backupManagerWorkDir, ConnectionConfig $connectionConfig, BackupDriverConfig|StorageServerDriverConfig $driverConfig)
    {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext();
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext();
        }

        $command = $driver->Setup().' && '.$driver->Push($localWorkDir).' && '.$driver->Clean();

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->Setup().' && '.$connection->Push($localWorkDir, $externalWorkDir).' && '.$connection->Run($command).' && '.$connection->Clean();
        }

        return $command;
    }

    public static function Pull(string $backupManagerWorkDir, ConnectionConfig $connectionConfig, BackupDriverConfig|StorageServerDriverConfig $driverConfig)
    {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if (count($connections)) {
            $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            $connections[0]->dockerContext();
        } else {
            $localWorkDir = $backupManagerWorkDir;
            $driver->dockerContext();
        }

        $command = $driver->Setup().' && '.$driver->Pull($localWorkDir).' && '.$driver->Clean();

        $connections = array_reverse($connections);

        for ($i = 0; $i < count($connections); $i++) {

            $connection = $connections[$i];

            $externalWorkDir = $localWorkDir;

            if ($i == count($connections) - 1) {
                $localWorkDir = $backupManagerWorkDir;
            } else {
                $localWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();
            }

            $command = $connection->Setup().' && '.$connection->Run($command).' && '.$connection->Pull($localWorkDir, $externalWorkDir).' && '.$connection->Clean();
        }

        return $command;
    }

    public static function Execute(string $command, ConnectionConfig $connectionConfig)
    {
        $connections = $connectionConfig->connections;

        if (count($connections)) {
            $connections[0]->dockerContext();
        }

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->Setup().' && '.$connection->Run($command).' && '.$connection->Clean();
        }

        return $command;
    }

    public static function backup(
        ConnectionConfig $backupConnectionConfig,
        BackupDriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        StorageServerDriverConfig $storageServerDriverConfig)
    {
        $backupManagerWorkDir = '/tmp/backup-manager/backups/'.Str::uuid();

        $command = CommandBuilder::Pull($backupManagerWorkDir, $backupConnectionConfig, $backupDriverConfig).' && '.
            CommandBuilder::Push($backupManagerWorkDir, $storageServerConnectionConfig, $storageServerDriverConfig);

        return $command;
    }
}
