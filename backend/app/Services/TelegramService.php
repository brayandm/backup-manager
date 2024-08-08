<?php

namespace App\Services;

use App\Helpers\Formatting;
use App\Models\Settings;
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
        foreach ($settings as $key => $value) {
            $setting = Settings::where('key', $key)->first();

            if ($setting) {
                $setting->value = $value;
                $setting->save();
            }
        }
    }
}
