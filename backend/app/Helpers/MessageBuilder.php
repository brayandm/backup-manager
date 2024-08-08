<?php

namespace App\Helpers;

class MessageBuilder
{
    public static function backupSuccessMessage($backup)
    {
        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $dateTime = $backup->created_at->format('Y-m-d H:i:s');

        $size = $backup->size;

        return "Backup of $backupConfigurationName on $serverName completed successfully at $dateTime. Size: $size";
    }
}
