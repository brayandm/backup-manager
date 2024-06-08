<?php

namespace App\Console\Commands;

use App\Models\BackupConfiguration;
use App\Services\BackupService;
use Illuminate\Console\Command;

class RunBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-backup-configuration {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a backup configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration) {
            $this->info("Running backup configuration: {$backupConfiguration->name}");

            $backupService = app(BackupService::class);

            $success = $backupService->backup($backupConfiguration);

            if ($success) {
                $this->info("Backup configuration {$backupConfiguration->name} completed successfully.");
            } else {
                $this->error("Backup configuration {$backupConfiguration->name} failed.");
            }
        } else {
            $this->error('Backup configuration not found');
        }
    }
}
