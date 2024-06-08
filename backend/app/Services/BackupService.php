<?php

namespace App\Services;

use App\Enums\BackupStatus;
use App\Helpers\CommandBuilder;
use App\Models\Backup;
use App\Models\BackupConfiguration;
use Illuminate\Support\Facades\Log;

class BackupService
{
    private function formatText($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9 ]/', '', $text);
        $text = str_replace(' ', '_', $text);

        return $text;
    }

    public function backup(BackupConfiguration $backupConfiguration)
    {
        Log::info("Running backup configuration: {$backupConfiguration->name}");

        $storageServers = $backupConfiguration->storageServers;

        $success = true;

        $backups = [];

        foreach ($storageServers as $storageServer) {

            $backups[] = Backup::create([
                'name' => '',
                'connection_config' => $backupConfiguration->connection_config,
                'driver_config' => $backupConfiguration->driver_config,
                'compression_config' => $backupConfiguration->compression_config,
                'encryption_config' => $backupConfiguration->encryption_config,
                'integrity_check_config' => $backupConfiguration->integrity_check_config,
                'status' => BackupStatus::CREATED,
            ]);
        }

        for ($i = 0; $i < count($storageServers); $i++) {

            $backup = $backups[$i];
            $storageServer = $storageServers[$i];

            Log::info("Running backup configuration: {$backupConfiguration->name} for storage server: {$storageServer->name}");

            $backup->name = 'backup-'.$this->formatText($backupConfiguration->name).'-'.$this->formatText($storageServer->name).'-'.'id'.$backup->id.'-'.date('Ymd-His');
            $backup->status = BackupStatus::RUNNING;
            $backup->save();

            $backupLayers = [];
            $backupManagerLayers = [];
            $serverStorageLayers = [];

            $command = CommandBuilder::backup(
                $backup->name,
                $backupConfiguration->connection_config,
                $backupConfiguration->driver_config,
                $storageServer->connection_config,
                $storageServer->driver_config,
                $backupLayers,
                $backupManagerLayers,
                $serverStorageLayers
            );

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} completed successfully.");

                $backup->status = BackupStatus::COMPLETED;
                $backup->save();
            } else {
                $success = false;
                Log::error("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} failed with error code: {$resultCode}");

                $backup->status = BackupStatus::FAILED;
                $backup->save();
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
