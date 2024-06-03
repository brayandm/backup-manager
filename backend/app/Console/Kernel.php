<?php

namespace App\Console;

use App\Models\BackupConfiguration;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $backupConfigurations = BackupConfiguration::all();

        foreach ($backupConfigurations as $backupConfiguration) {
            $schedule->call(function () use ($backupConfiguration) {
                $backupConfiguration->Backup();
            })->cron($backupConfiguration->schedule_cron);
        }
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
