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
            $backup = Backup::create([
                'name' => '',
                'backup_configuration_id' => $backupConfiguration->id,
                'connection_config' => $storageServer->connection_config,
                'driver_config' => $storageServer->driver_config,
                'compression_config' => $backupConfiguration->compression_config,
                'encryption_config' => $backupConfiguration->encryption_config,
                'integrity_check_config' => $backupConfiguration->integrity_check_config,
                'status' => BackupStatus::CREATED,
            ]);

            $backup->encryption_config->encryptionMethod->generateKey();

            $backup->save();

            $backups[] = $backup;
        }

        for ($i = 0; $i < count($storageServers); $i++) {

            $backup = $backups[$i];
            $storageServer = $storageServers[$i];

            Log::info("Running backup configuration: {$backupConfiguration->name} for storage server: {$storageServer->name}");

            $backup->name = 'backup-'.$this->formatText($backupConfiguration->name).'-'.$this->formatText($storageServer->name).'-'.'id'.$backup->id.'-'.date('Ymd-His').'-UTC';
            $backup->status = BackupStatus::RUNNING;
            $backup->save();

            $command = CommandBuilder::backup(
                $backup->name,
                $backupConfiguration->connection_config,
                $backupConfiguration->driver_config,
                $storageServer->connection_config,
                $storageServer->driver_config,
                $backupConfiguration->compression_config,
                $backupConfiguration->encryption_config,
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

    public function restore(Backup $backup)
    {
        Log::info("Restoring backup: {$backup->name}");

        $success = true;

        $command = CommandBuilder::restore(
            $backup->name,
            $backup->backupConfiguration->connection_config,
            $backup->backupConfiguration->driver_config,
            $backup->connection_config,
            $backup->driver_config,
            $backup->compression_config,
            $backup->encryption_config,
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            Log::info("Backup {$backup->name} restored successfully.");
        } else {
            $success = false;
            Log::error("Backup {$backup->name} failed to restore.");
        }

        return $success;
    }

    public function delete(Backup $backup)
    {
        Log::info("Deleting backup: {$backup->name}");

        $success = true;

        $command = CommandBuilder::delete(
            $backup->name,
            $backup->connection_config,
            $backup->driver_config
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            Log::info("Backup {$backup->name} deleted successfully.");

            $backup->delete();
        } else {
            $success = false;
            Log::error("Backup {$backup->name} failed to delete.");
        }

        return $success;
    }
}
