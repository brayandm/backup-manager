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

    public static function backupSuccessMessage($backup)
    {
        if (config('logging.telegram.active') === 'false') {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $dateTime = $backup->created_at->format('Y-m-d H:i:s');

        $size = $backup->size;

        $message = "Backup of $backupConfigurationName on $serverName completed successfully at $dateTime. Size: $size";

        TelegramService::sendMessage($message);
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
