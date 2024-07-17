<?php

namespace App\Services;

use App\Casts\DataSourceDriverCast;
use App\Casts\CompressionMethodCast;
use App\Casts\ConnectionCast;
use App\Casts\EncryptionMethodCast;
use App\Casts\IntegrityCheckMethodCast;
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
        $dataSources = $backupConfiguration->dataSources;

        $success = true;

        $backups = [];

        if (count($dataSources) === 0) {
            throw new \Exception('No data sources found for backup configuration.');

            return false;
        }

        if (count($storageServers) === 0) {
            throw new \Exception('No storage servers found for backup configuration.');

            return false;
        }

        foreach ($dataSources as $dataSource)
        {
            foreach ($storageServers as $storageServer) {
                $backup = Backup::create([
                    'name' => '',
                    'backup_configuration_id' => $backupConfiguration->id,
                    'data_source_id' => $dataSource->id,
                    'storage_server_id' => $storageServer->id,
                    'driver_config' => $storageServer->driver_config,
                    'compression_config' => $backupConfiguration->compression_config,
                    'encryption_config' => $backupConfiguration->encryption_config,
                    'integrity_check_config' => $backupConfiguration->integrity_check_config,
                    'status' => BackupStatus::CREATED,
                ]);

                $backup = Backup::find($backup->id);

                $backup->name = 'backup-'.$this->formatText($backupConfiguration->name).'-'.$this->formatText($dataSource->name).'-'.$this->formatText($storageServer->name).'-'.'id'.$backup->id.'-'.date('Ymd-His').'-UTC';

                if (count($backups) > 0) {
                    $backup->encryption_config = $backups[0]->encryption_config;
                } else {
                    $backup->encryption_config->encryptionMethod->generateKey();
                }

                $backup->save();

                $backups[] = $backup;
            }
        }

        foreach ($dataSources as $dataSource)
        {
            $response = CommandBuilder::backupPull(
                $dataSource->connection_config,
                $dataSource->driver_config,
                $backupConfiguration->compression_config,
                $backups[0]->encryption_config
            );

            $output = null;
            $resultCode = null;
            exec($response['command'], $output, $resultCode);

            if ($resultCode === 0) {
                Log::info("Backup was pulled to Backup Manager from {$dataSource->name} successfully.");
            } else {
                $success = false;
                Log::error("Backup pull to Backup Manager from {$dataSource->name} failed with error code: {$resultCode}");

                foreach ($backups as $backup) {
                    $backup->status = BackupStatus::FAILED;
                    $backup->save();
                }

                return false;
            }

            $command = CommandBuilder::integrityCheckGenerate($response['backupManagerWorkDir'], $backups[0]->integrity_check_config);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info('Integrity check hash generated successfully.');
            } else {
                $success = false;
                Log::error("Integrity check hash generation failed with error code: {$resultCode}");

                foreach ($backups as $backup) {
                    $backup->status = BackupStatus::FAILED;
                    $backup->save();
                }

                return false;
            }

            foreach ($backups as $backup) {
                $backup->integrity_check_config->integrityCheckMethod->setHash($output[0]);
                $backup->save();
            }

            $command = CommandBuilder::calculateSize($response['backupManagerWorkDir']);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info('Backup size calculated successfully.');
            } else {
                $success = false;
                Log::error("Backup size calculation failed with error code: {$resultCode}");

                foreach ($backups as $backup) {
                    $backup->status = BackupStatus::FAILED;
                    $backup->save();
                }

                return false;
            }

            foreach ($backups as $backup) {
                $sizeInBytes = (int) explode("\t", $output[0])[0];
                $backup->size = $sizeInBytes;
                $backup->save();
            }

            for ($i = 0; $i < count($storageServers); $i++) {

                $backup = $backups[$i];
                $storageServer = $storageServers[$i];

                Log::info("Running backup configuration: {$backupConfiguration->name} for storage server: {$storageServer->name} and data source: {$dataSource->name}");

                $backup->status = BackupStatus::RUNNING;
                $backup->save();

                $command = CommandBuilder::backupPush(
                    $i !== count($storageServers) - 1,
                    $backup->name,
                    $response['backupManagerWorkDir'],
                    $backup->storageServer->connection_config,
                    $backup->driver_config
                );

                $output = null;
                $resultCode = null;
                exec($command, $output, $resultCode);

                if ($resultCode === 0) {
                    Log::info("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} and data source {$dataSource->name} completed successfully.");

                    $backup->status = BackupStatus::COMPLETED;
                    $backup->save();
                } else {
                    $success = false;
                    Log::error("Backup configuration {$backupConfiguration->name} for storage server {$storageServer->name} and data source {$dataSource->name} failed with error code: {$resultCode}");

                    $backup->status = BackupStatus::FAILED;
                    $backup->save();
                }
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
            $backup->dataSource->connection_config,
            $backup->dataSource->driver_config,
            $backup->storageServer->connection_config,
            $backup->driver_config,
            $backup->compression_config,
            $backup->encryption_config,
            $backup->integrity_check_config
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
            $backup->storageServer->connection_config,
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

    public function getBackupConfigurations($pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = BackupConfiguration::query();

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function getBackups($pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = Backup::query();

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function storeBackupConfiguration($data)
    {
        $backupConfiguration = new BackupConfiguration();

        $backupConfiguration->name = $data['name'];

        $backupConfiguration->schedule_cron = $data['schedule_cron'];

        $backupConfiguration->retention_policy_config = $data['retention_policy_config'];

        $compressionMethodCast = app(CompressionMethodCast::class);
        $backupConfiguration->compression_config = $compressionMethodCast->get($backupConfiguration, 'compression_config', $data['compression_config'], []);

        $encryptionMethodCast = app(EncryptionMethodCast::class);
        $backupConfiguration->encryption_config = $encryptionMethodCast->get($backupConfiguration, 'encryption_config', $data['encryption_config'], []);

        $integrityCheckMethodCast = app(IntegrityCheckMethodCast::class);
        $backupConfiguration->integrity_check_config = $integrityCheckMethodCast->get($backupConfiguration, 'integrity_check_config', $data['integrity_check_config'], []);

        $backupConfiguration->save();

        $backupConfiguration->storageServers()->attach($data['storage_server_ids']);

        return $backupConfiguration;
    }

    public function getBackupConfiguration($id)
    {
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration === null) {
            throw new \Exception('Backup configuration not found.');
        }

        $compressionMethodCast = app(CompressionMethodCast::class);
        $encryptionMethodCast = app(EncryptionMethodCast::class);
        $integrityCheckMethodCast = app(IntegrityCheckMethodCast::class);

        return [
            'name' => $backupConfiguration->name,
            'storage_servers' => $backupConfiguration->storageServers->map(function ($storageServer) {
                return [
                    'id' => $storageServer->id,
                    'name' => $storageServer->name,
                ];
            }),
            'schedule_cron' => $backupConfiguration->schedule_cron,
            'retention_policy_config' => $backupConfiguration->retention_policy_config,
            'compression_config' => $compressionMethodCast->set($backupConfiguration, 'compression_config', $backupConfiguration->compression_config, []),
            'encryption_config' => $encryptionMethodCast->set($backupConfiguration, 'encryption_config', $backupConfiguration->encryption_config, []),
            'integrity_check_config' => $integrityCheckMethodCast->set($backupConfiguration, 'integrity_check_config', $backupConfiguration->integrity_check_config, []),
        ];
    }

    public function updateBackupConfiguration($id, $data)
    {
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration === null) {
            throw new \Exception('Backup configuration not found.');
        }

        $backupConfiguration->name = $data['name'];

        $backupConfiguration->schedule_cron = $data['schedule_cron'];

        $backupConfiguration->retention_policy_config = $data['retention_policy_config'];

        $compressionMethodCast = app(CompressionMethodCast::class);
        $backupConfiguration->compression_config = $compressionMethodCast->get($backupConfiguration, 'compression_config', $data['compression_config'], []);

        $encryptionMethodCast = app(EncryptionMethodCast::class);
        $backupConfiguration->encryption_config = $encryptionMethodCast->get($backupConfiguration, 'encryption_config', $data['encryption_config'], []);

        $integrityCheckMethodCast = app(IntegrityCheckMethodCast::class);
        $backupConfiguration->integrity_check_config = $integrityCheckMethodCast->get($backupConfiguration, 'integrity_check_config', $data['integrity_check_config'], []);

        $backupConfiguration->save();

        $backupConfiguration->storageServers()->sync($data['storage_server_ids']);

        return $backupConfiguration;
    }

    public function deleteBackupConfiguration($id)
    {
        $backupConfiguration = BackupConfiguration::find($id);

        if ($backupConfiguration === null) {
            throw new \Exception('Backup configuration not found.');
        }

        $backupConfiguration->delete();

        return true;
    }

    public function deleteBackup($id)
    {
        $backup = Backup::find($id);

        if ($backup === null) {
            throw new \Exception('Backup not found.');
        }

        $this->delete($backup);

        return true;
    }

    public function deleteBackupConfigurations($ids)
    {
        BackupConfiguration::whereIn('id', $ids)->delete();

        return true;
    }

    public function deleteBackups($ids)
    {
        foreach ($ids as $id) {
            $backup = Backup::find($id);

            if ($backup === null) {
                throw new \Exception('Backup not found.');
            }

            $this->delete($backup);
        }

        return true;
    }

    public function deleteAllBackupConfigurationsExcept($ids)
    {
        BackupConfiguration::whereNotIn('id', $ids)->delete();

        return true;
    }

    public function deleteAllBackupsExcept($ids)
    {
        $allIds = Backup::all()->pluck('id')->toArray();

        $ids = array_diff($allIds, $ids);

        foreach ($ids as $id) {
            $backup = Backup::find($id);

            if ($backup === null) {
                throw new \Exception('Backup not found.');
            }

            $this->delete($backup);
        }
    }

    public function getBackupsWithBackupConfigurationId($id, $pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = Backup::query();

        $query->where('backup_configuration_id', $id);

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function restoreBackup($id)
    {
        $backup = Backup::find($id);

        if ($backup === null) {
            throw new \Exception('Backup not found.');
        }

        return $this->restore($backup);
    }
}
