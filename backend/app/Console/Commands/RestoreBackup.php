<?php

namespace App\Console\Commands;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Console\Command;

class RestoreBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:restore-backup {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore a backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backup = Backup::find($id);

        if ($backup) {
            $this->info("Restoring backup: {$backup->name}");

            $backupService = app(BackupService::class);

            $success = $backupService->restore($backup);

            if ($success) {
                $this->info("Backup {$backup->name} restored successfully.");
            } else {
                $this->error("Backup {$backup->name} failed to restore.");
            }
        } else {
            $this->error('Backup not found');
        }
    }
}
