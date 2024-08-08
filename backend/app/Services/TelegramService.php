<?php

namespace App\Services;

use App\Helpers\Formatting;
use App\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private static function sendMessage($message)
    {
        Log::channel('telegram')->info($message);
    }

    private static function isTelegramActive()
    {
        return config('logging.channels.telegram.active') === 'true';
    }

    public static function backupSuccessMessage($backup)
    {
        if (! self::isTelegramActive()) {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $size = Formatting::formatBytes($backup->size);

        $message = "Backup of $backupConfigurationName on $serverName completed successfully. Size: $size";

        self::sendMessage($message);
    }

    public function getSettings()
    {
        return Settings::all()->pluck('value', 'key')->toArray();
    }

    public function updateSettings($settings)
    {
        return [
            'telegram_bot_active' => Config::get('logging.channels.telegram.active'),
            'telegram_bot_api_key' => Config::get('logging.channels.telegram.handler_with.apiKey'),
            'telegram_channel_id' => Config::get('logging.channels.telegram.handler_with.channel'),
            'telegram_notify_backups' => Config::get('logging.channels.telegram.notify_backups'),
            'telegram_notify_migrations' => Config::get('logging.channels.telegram.notify_migrations'),
        ];
    }
}
