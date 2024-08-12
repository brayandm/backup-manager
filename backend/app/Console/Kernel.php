<?php

namespace App\Console;

use App\Enums\BackupConfigurationStatus;
use App\Enums\MigrationConfigurationStatus;
use App\Jobs\BackupJob;
use App\Jobs\CalculateFreeSpaceStorageServerJob;
use App\Jobs\CheckDataSourceAvailabilityJob;
use App\Jobs\CheckStorageServerAvailabilityJob;
use App\Jobs\MigrationJob;
use App\Jobs\RetentionPolicyJob;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
use App\Models\MigrationConfiguration;
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
            if ($backupConfiguration->status == BackupConfigurationStatus::ACTIVE->value) {
                if (! $backupConfiguration->manual_backup) {
                    $schedule->job(new BackupJob($backupConfiguration))->cron($backupConfiguration->schedule_cron);
                }
                if (! $backupConfiguration->retention_policy_config->getDisableRetentionPolicy()) {
                    $schedule->job(new RetentionPolicyJob($backupConfiguration))->daily();
                }
            }
        }

        $migrationConfigurations = MigrationConfiguration::all();

        foreach ($migrationConfigurations as $migrationConfiguration) {
            if ($migrationConfiguration->status == MigrationConfigurationStatus::ACTIVE->value) {
                if (! $migrationConfiguration->manual_migration) {
                    $schedule->job(new MigrationJob($migrationConfiguration))->cron($migrationConfiguration->schedule_cron);
                }
            }
        }

        $storageServers = StorageServer::all();

        foreach ($storageServers as $storageServer) {
            $schedule->job(new CalculateFreeSpaceStorageServerJob($storageServer))->everyFiveMinutes();

            $schedule->job(new CheckStorageServerAvailabilityJob($storageServer))->everyFiveMinutes();
        }

        $dataSources = DataSource::all();

        foreach ($dataSources as $dataSource) {
            $schedule->job(new CheckDataSourceAvailabilityJob($dataSource))->everyFiveMinutes();
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
