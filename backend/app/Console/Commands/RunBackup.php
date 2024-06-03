<?php

namespace App\Console\Commands;

use App\Models\BackupConfiguration;
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration) {
            $this->info("Running backup configuration: {$backupConfiguration->name}");

            $success = $backupConfiguration->Backup();

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
