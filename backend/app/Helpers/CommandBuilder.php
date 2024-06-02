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
