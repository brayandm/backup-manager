<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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
        $settings = DB::table('settings')->pluck('value', 'key')->toArray();

        Config::set('services.telegram.active', $settings['telegram_bot_active']);
        Config::set('services.telegram.bot_api_key', $settings['telegram_bot_api_key']);
        Config::set('services.telegram.channel_id', $settings['telegram_channel_id']);
    }
}
