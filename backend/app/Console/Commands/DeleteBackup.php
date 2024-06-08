<?php

namespace App\Console\Commands;

use App\Models\Backup;
use App\Services\BackupService;
use Illuminate\Console\Command;

class DeleteBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-backup {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a backup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backup = Backup::find($id);

        if ($backup) {
            $this->info("Deleting backup: {$backup->name}");

            $backupService = new BackupService();

            $success = $backupService->delete($backup);

            if ($success) {
                $this->info("Backup {$backup->name} deleted successfully.");
            } else {
                $this->error("Backup {$backup->name} failed to delete.");
            }
        } else {
            $this->error('Backup not found');
        }
    }
}
