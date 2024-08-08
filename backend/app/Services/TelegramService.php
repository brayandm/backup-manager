<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramService
{
    private static function sendMessage($message)
    {
        Log::channel('telegram')->info($message);
    }

    public static function backupSuccessMessage($backup)
    {
        if (config('services.telegram.active') === 'false') {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $dateTime = $backup->created_at->format('Y-m-d H:i:s');

        $size = $backup->size;

        $message = "Backup of $backupConfigurationName on $serverName completed successfully at $dateTime. Size: $size";

        TelegramService::sendMessage($message);
    }
}
