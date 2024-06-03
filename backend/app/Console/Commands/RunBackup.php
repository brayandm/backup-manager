<?php

namespace App\Console\Commands;

use App\Helpers\CommandBuilder;
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

            $command = CommandBuilder::Backup(
                $id,
                $backupConfiguration->connection_config,
                $backupConfiguration->driver_config,
                $backupConfiguration->storageServer->connection_config,
                $backupConfiguration->storageServer->driver_config);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                $this->info("Backup configuration {$backupConfiguration->name} completed successfully.");
            } else {
                $this->error("Backup configuration {$backupConfiguration->name} failed with error code: {$resultCode}");
            }
        } else {
            $this->error('Backup configuration not found');
        }
    }
}
