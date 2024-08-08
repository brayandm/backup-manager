<?php

namespace App\Services;

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
        return config('logging.telegram.active') === 'true';
    }

    public static function backupSuccessMessage($backup)
    {
        if (!self::isTelegramActive()) {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $dateTime = $backup->created_at->format('Y-m-d H:i:s');

        $size = $backup->size;

        $message = "Backup of $backupConfigurationName on $serverName completed successfully at $dateTime. Size: $size";

        self::sendMessage($message);
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
