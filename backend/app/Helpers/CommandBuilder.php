<?php

namespace App\Helpers;

use App\Entities\ConnectionConfig;
use App\Entities\DriverConfig;

class CommandBuilder
{
    public static function Push(string $filepath, ConnectionConfig $connectionConfig, DriverConfig $driverConfig)
    {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if(count($connections) == 0){
            $driver->DockerContext();
        }

        $command = $driver->Setup() . ' && ' . $driver->Push($filepath) . ' && ' . $driver->Clean();

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->Setup() . ' && ' . $connection->Push($filepath) . ' && ' . $connection->Run($command) . ' && ' . $connection->Clean();
        }
    }

    public static function Pull(string $filepath, ConnectionConfig $connectionConfig, DriverConfig $driverConfig)
    {
        $connections = $connectionConfig->connections;
        $driver = $driverConfig->driver;

        if(count($connections) == 0){
            $driver->DockerContext();
        }

        $command = $driver->Setup() . ' && ' . $driver->Pull($filepath) . ' && ' . $driver->Clean();

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->Setup() . ' && ' . $connection->Run($command) . ' && ' . $connection->Pull($filepath) . ' && ' . $connection->Clean();
        }
    }

    public static function Execute(string $command, ConnectionConfig $connectionConfig)
    {
        $connections = $connectionConfig->connections;

        foreach (array_reverse($connections) as $connection) {
            $command = $connection->Setup() . ' && ' . $connection->Run($command) . ' && ' . $connection->Clean();
        }
    }

    public static function Backup(string $id,
        ConnectionConfig $backupConnectionConfig,
        DriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        DriverConfig $storageServerDriverConfig)
    {
        $filename = '/tmp/backup-manager/backups/backup-id'.$id.'-'.date('Y-m-d-H-i-s').'.tar.gz';

        $command = CommandBuilder::Pull($filename, $backupConnectionConfig, $backupDriverConfig).' && '.
            CommandBuilder::Push($filename, $storageServerConnectionConfig, $storageServerDriverConfig);

        return $command;
    }

    public static function Restore(string $id,
        ConnectionConfig $storageServerConnectionConfig,
        DriverConfig $storageServerDriverConfig,
        ConnectionConfig $backupConnectionConfig,
        DriverConfig $backupDriverConfig)
    {
        $filename = '/tmp/backup-manager/backups/backup-id'.$id.'-'.date('Y-m-d-H-i-s').'.tar.gz';

        $command = CommandBuilder::Pull($filename, $storageServerConnectionConfig, $storageServerDriverConfig).' && '.
            CommandBuilder::Push($filename, $backupConnectionConfig, $backupDriverConfig);

        return $command;
    }
}
