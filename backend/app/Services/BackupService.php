<?php

namespace App\Services;

use App\Helpers\CommandBuilder;
use App\Models\BackupConfiguration;
use Illuminate\Support\Facades\Log;

class BackupService
{
    public function Backup(BackupConfiguration $backupConfiguration)
    {
        Log::info("Running backup configuration: {$backupConfiguration->name}");

        $storageServers = $backupConfiguration->storageServers;

        $success = true;

        foreach ($storageServers as $storageServer) {
            Log::info("Running backup configuration: {$backupConfiguration->name} for storage server: {$storageServer->name}");

            $command = CommandBuilder::Backup(
                $backupConfiguration->connection_config,
                $backupConfiguration->driver_config,
                $storageServer->connection_config,
                $storageServer->driver_config);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} completed successfully.");
            } else {
                $success = false;
                Log::error("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} failed with error code: {$resultCode}");
            }
        }

        if ($success) {
            Log::info("Backup configuration {$backupConfiguration->name} completed successfully.");
        } else {
            Log::error("Backup configuration {$backupConfiguration->name} failed.");
        }

        return $success;
    }
}
