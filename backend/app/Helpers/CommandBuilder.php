<?php

namespace App\Helpers;

use App\Entities\ConnectionConfig;
use App\Entities\DriverConfig;

class CommandBuilder
{
    public static function Push(string $filepath, ConnectionConfig $connectionConfig, DriverConfig $driverConfig)
    {
    }

    public static function Pull(string $filepath, ConnectionConfig $connectionConfig, DriverConfig $driverConfig)
    {
    }

    public static function Execute(string $command, ConnectionConfig $connectionConfig)
    {
    }

    public static function Backup(ConnectionConfig $backupConnectionConfig,
        DriverConfig $backupDriverConfig,
        ConnectionConfig $storageServerConnectionConfig,
        DriverConfig $storageServerDriverConfig)
    {
    }

    public static function Restore(ConnectionConfig $storageServerConnectionConfig,
        DriverConfig $storageServerDriverConfig,
        ConnectionConfig $backupConnectionConfig,
        DriverConfig $backupDriverConfig)
    {
    }
}
