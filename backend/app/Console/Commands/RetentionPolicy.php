<?php

namespace App\Console\Commands;

use App\Models\BackupConfiguration;
use App\Services\BackupService;
use Illuminate\Console\Command;

class RetentionPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retention-policy {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run retention policy for a backup configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration) {
            $this->info("Running retention policy for backup configuration: {$backupConfiguration->name}");

            $backupService = app(BackupService::class);

            $success = $backupService->retentionPolicy($backupConfiguration);

            if ($success) {
                $this->info("Retention policy for backup configuration {$backupConfiguration->name} completed successfully.");
            } else {
                $this->error("Retention policy for backup configuration {$backupConfiguration->name} failed.");
            }
        } else {
            $this->error('Backup configuration not found');
        }
    }
}
