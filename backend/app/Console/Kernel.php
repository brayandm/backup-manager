<?php

namespace App\Console;

use App\Jobs\BackupJob;
use App\Jobs\CalculateFreeSpaceStorageServerJob;
use App\Models\BackupConfiguration;
use App\Models\StorageServer;
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
            $schedule->job(new BackupJob($backupConfiguration))->cron($backupConfiguration->schedule_cron);
        }

        $storageServers = StorageServer::all();

        foreach ($storageServers as $storageServer) {
            $schedule->job(new CalculateFreeSpaceStorageServerJob($storageServer))->everyHour();
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
