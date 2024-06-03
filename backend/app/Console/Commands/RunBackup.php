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

        $onError = false;

        if ($backupConfiguration) {
            $this->info("Running backup configuration: {$backupConfiguration->name}");

            $storageServers = $backupConfiguration->storageServers;

            foreach ($storageServers as $storageServer) {
                $this->info("Running backup configuration: {$backupConfiguration->name} for storage server: {$storageServer->name}");

                $command = CommandBuilder::Backup(
                    $id,
                    $backupConfiguration->connection_config,
                    $backupConfiguration->driver_config,
                    $storageServer->connection_config,
                    $storageServer->driver_config);

                $output = null;
                $resultCode = null;
                exec($command, $output, $resultCode);

                if ($resultCode === 0) {
                    $this->info("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} completed successfully.");
                } else {
                    $onError = true;
                    $this->error("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} failed with error code: {$resultCode}");
                }
            }

            if ($onError === false) {
                $this->info("Backup configuration {$backupConfiguration->name} completed successfully.");
            } else {
                $this->error("Backup configuration {$backupConfiguration->name} failed.");
            }
        } else {
            $this->error('Backup configuration not found');
        }
    }
}
