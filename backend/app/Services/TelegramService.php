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

    private static function shouldNotifyBackups()
    {
        return config('logging.channels.telegram.notify_backups') === 'true';
    }

    private static function shouldNotifyMigrations()
    {
        return config('logging.channels.telegram.notify_migrations') === 'true';
    }

    public static function backupSuccessMessage($backup)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyBackups()) {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $size = Formatting::formatBytes($backup->size);

        $message = "Backup of $backupConfigurationName on $serverName completed successfully. Size: $size";

        self::sendMessage($message);
    }

    public static function backupFailureMessage($backup)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyBackups()) {
            return;
        }

        $backupConfigurationName = $backup->backupConfiguration->name;

        $serverName = $backup->storageServer->name;

        $message = "Backup of $backupConfigurationName on $serverName failed";

        self::sendMessage($message);
    }

    public static function backupConfigurationSuccessMessage($backupConfiguration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyBackups()) {
            return;
        }

        $backupConfigurationName = $backupConfiguration->name;

        $message = "Backup configuration $backupConfigurationName completed successfully";

        self::sendMessage($message);
    }

    public static function backupConfigurationFailureMessage($backupConfiguration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyBackups()) {
            return;
        }

        $backupConfigurationName = $backupConfiguration->name;

        $message = "Backup configuration $backupConfigurationName failed";

        self::sendMessage($message);
    }

    public static function migrationSuccessMessage($migration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyMigrations()) {
            return;
        }

        $migrationConfigurationName = $migration->migrationConfiguration->name;

        $originDataSource = $migration->originDataSource->name;

        $endDataSource = $migration->endDataSource->name;

        $message = "Migration of $migrationConfigurationName from $originDataSource to $endDataSource completed successfully";

        self::sendMessage($message);
    }

    public static function migrationFailureMessage($migration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyMigrations()) {
            return;
        }

        $migrationConfigurationName = $migration->migrationConfiguration->name;

        $originDataSource = $migration->originDataSource->name;

        $endDataSource = $migration->endDataSource->name;

        $message = "Migration of $migrationConfigurationName from $originDataSource to $endDataSource failed";

        self::sendMessage($message);
    }

    public static function migrationConfigurationSuccessMessage($migrationConfiguration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyMigrations()) {
            return;
        }

        $migrationConfigurationName = $migrationConfiguration->name;

        $message = "Migration configuration $migrationConfigurationName completed successfully";

        self::sendMessage($message);
    }

    public static function migrationConfigurationFailureMessage($migrationConfiguration)
    {
        if (! self::isTelegramActive() || ! self::shouldNotifyMigrations()) {
            return;
        }

        $migrationConfigurationName = $migrationConfiguration->name;

        $message = "Migration configuration $migrationConfigurationName failed";

        self::sendMessage($message);
    }

    public function getSettings()
    {
        return [
            'telegram_bot_active' => Config::get('logging.channels.telegram.active'),
            'telegram_bot_api_key' => Config::get('logging.channels.telegram.handler_with.apiKey'),
            'telegram_channel_id' => Config::get('logging.channels.telegram.handler_with.channel'),
            'telegram_notify_backups' => Config::get('logging.channels.telegram.notify_backups'),
            'telegram_notify_migrations' => Config::get('logging.channels.telegram.notify_migrations'),
        ];
    }

    public function updateSettings($settings)
    {
        foreach ($settings as $key => $value) {
            Settings::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
