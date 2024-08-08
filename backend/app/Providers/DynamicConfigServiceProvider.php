<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class DynamicConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('settings')) {

            Settings::firstOrCreate(['key' => 'telegram_bot_active'], ['value' => 'false']);
            Settings::firstOrCreate(['key' => 'telegram_bot_api_key'], ['value' => '']);
            Settings::firstOrCreate(['key' => 'telegram_channel_id'], ['value' => '']);
            Settings::firstOrCreate(['key' => 'telegram_notify_backups'], ['value' => 'false']);
            Settings::firstOrCreate(['key' => 'telegram_notify_migrations'], ['value' => 'false']);

            $settings = Settings::all()->pluck('value', 'key')->toArray();

            Config::set('logging.channels.telegram.active', $settings['telegram_bot_active']);
            Config::set('logging.channels.telegram.handler_with.apiKey', $settings['telegram_bot_api_key']);
            Config::set('logging.channels.telegram.handler_with.channel', $settings['telegram_channel_id']);
            Config::set('logging.channels.telegram.notify_backups', $settings['telegram_notify_backups']);
            Config::set('logging.channels.telegram.notify_migrations', $settings['telegram_notify_migrations']);
        }
    }
}
