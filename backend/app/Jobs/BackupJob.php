<?php

namespace App\Jobs;

use App\Helpers\CommandBuilder;
use App\Models\BackupConfiguration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private BackupConfiguration $backupConfiguration;

    /**
     * Create a new job instance.
     */
    public function __construct(BackupConfiguration $backupConfiguration)
    {
        $this->backupConfiguration = $backupConfiguration;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Running backup configuration: {$this->backupConfiguration->name}");

        $storageServers = $this->backupConfiguration->storageServers;

        $onError = false;

        foreach ($storageServers as $storageServer) {
            Log::info("Running backup configuration: {$this->backupConfiguration->name} for storage server: {$storageServer->name}");

            $command = CommandBuilder::Backup(
                $this->backupConfiguration->id,
                $this->backupConfiguration->connection_config,
                $this->backupConfiguration->driver_config,
                $storageServer->connection_config,
                $storageServer->driver_config);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info("Backup configuration {$this->backupConfiguration->name} for storage server {$storageServer->name} completed successfully.");
            } else {
                $onError = true;
                Log::error("Backup configuration {$this->backupConfiguration->name} for storage server {$storageServer->name} failed with error code: {$resultCode}");
            }
        }

        if ($onError === false) {
            Log::info("Backup configuration {$this->backupConfiguration->name} completed successfully.");
        } else {
            Log::error("Backup configuration {$this->backupConfiguration->name} failed.");
        }
    }
}
