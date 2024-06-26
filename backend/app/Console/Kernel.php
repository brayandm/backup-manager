<?php

namespace App\Console;

use App\Jobs\BackupJob;
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
                BackupJob::dispatch($backupConfiguration);
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
