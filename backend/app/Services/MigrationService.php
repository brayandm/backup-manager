<?php

namespace App\Services;

use App\Casts\CompressionMethodCast;
use App\Helpers\CommandBuilder;
use App\Models\MigrationConfiguration;
use Illuminate\Support\Facades\Log;

class MigrationService
{
    public function getMigrationConfigurations($pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = MigrationConfiguration::query();

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function storeMigrationConfiguration($data)
    {
        $migrationConfiguration = new MigrationConfiguration();

        $migrationConfiguration->name = $data['name'];

        $migrationConfiguration->data_source_id = $data['data_source_id'];

        $migrationConfiguration->schedule_cron = $data['schedule_cron'];

        $migrationConfiguration->manual_migration = $data['manual_migration'];

        $compressionMethodCast = app(CompressionMethodCast::class);
        $migrationConfiguration->compression_config = $compressionMethodCast->get($migrationConfiguration, 'compression_config', $data['compression_config'], []);

        $migrationConfiguration->save();

        $migrationConfiguration->dataSources()->attach($data['data_source_ids']);

        return $migrationConfiguration;
    }

    public function getMigrationConfiguration($id)
    {
        $migrationConfiguration = MigrationConfiguration::find($id);

        if ($migrationConfiguration === null) {
            throw new \Exception('Migration configuration not found.');
        }

        $compressionMethodCast = app(CompressionMethodCast::class);

        return [
            'name' => $migrationConfiguration->name,
            'data_source_id' => $migrationConfiguration->data_source_id,
            'data_sources' => $migrationConfiguration->dataSources->map(function ($dataSource) {
                return [
                    'id' => $dataSource->id,
                    'name' => $dataSource->name,
                ];
            }),
            'storage_servers' => $migrationConfiguration->storageServers->map(function ($storageServer) {
                return [
                    'id' => $storageServer->id,
                    'name' => $storageServer->name,
                ];
            }),
            'schedule_cron' => $migrationConfiguration->schedule_cron,
            'manual_migration' => $migrationConfiguration->manual_migration,
            'compression_config' => $compressionMethodCast->set($migrationConfiguration, 'compression_config', $migrationConfiguration->compression_config, []),
        ];
    }

    public function updateMigrationConfiguration($id, $data)
    {
        $migrationConfiguration = MigrationConfiguration::find($id);

        if ($migrationConfiguration === null) {
            throw new \Exception('Migration configuration not found.');
        }

        $migrationConfiguration->name = $data['name'];

        $migrationConfiguration->data_source_id = $data['data_source_id'];

        $migrationConfiguration->schedule_cron = $data['schedule_cron'];

        $migrationConfiguration->manual_migration = $data['manual_migration'];

        $compressionMethodCast = app(CompressionMethodCast::class);
        $migrationConfiguration->compression_config = $compressionMethodCast->get($migrationConfiguration, 'compression_config', $data['compression_config'], []);

        $migrationConfiguration->save();

        $migrationConfiguration->dataSources()->attach($data['data_source_ids']);

        return $migrationConfiguration;
    }

    public function deleteMigrationConfiguration($id)
    {
        $migrationConfiguration = MigrationConfiguration::find($id);

        if ($migrationConfiguration === null) {
            throw new \Exception('Migration configuration not found.');
        }

        $migrationConfiguration->delete();

        return true;
    }

    public function deleteMigrationConfigurations($ids)
    {
        MigrationConfiguration::whereIn('id', $ids)->delete();

        return true;
    }

    public function deleteAllMigrationConfigurationsExcept($ids)
    {
        MigrationConfiguration::whereNotIn('id', $ids)->delete();

        return true;
    }

    public function migrate($migrationConfiguration)
    {
        Log::info("Running migration configuration: {$migrationConfiguration->name}");

        $originDataSource = $migrationConfiguration->dataSource;
        $endDataSources = $migrationConfiguration->dataSources;

        $success = true;

        if (count($endDataSources) === 0) {
            throw new \Exception('No end data sources found for migration configuration.');

            return false;
        }

        if ($originDataSource === null) {
            throw new \Exception('No origin data source found for migration configuration.');

            return false;
        }

        $response = CommandBuilder::dataPull(
            $originDataSource->connection_config,
            $originDataSource->driver_config,
            $migrationConfiguration->compression_config,
        );

        $output = null;
        $resultCode = null;
        exec($response['command'], $output, $resultCode);

        if ($resultCode === 0) {
            Log::info("Data was pulled to Backup Manager from {$originDataSource->name} successfully.");
        } else {
            $success = false;
            Log::error("Data pull to Backup Manager from {$originDataSource->name} failed with error code: {$resultCode}");
            return false;
        }

        for ($i = 0; $i < count($endDataSources); $i++) {

            $endDataSource = $endDataSources[$i];

            Log::info("Running migration configuration: {$migrationConfiguration->name} for end data source: {$endDataSource->name} and origin data source: {$originDataSource->name}");

            $command = CommandBuilder::push(
                $i !== count($endDataSources) - 1,
                null,
                $response['backupManagerWorkDir'],
                $endDataSource->connection_config,
                $endDataSource->driver_config,
                $migrationConfiguration->compression_config,
            );

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                Log::info("Migration configuration {$migrationConfiguration->name} for end data source {$endDataSource->name} and origin data source {$originDataSource->name} completed successfully.");
            } else {
                $success = false;
                Log::error("Migration configuration {$migrationConfiguration->name} for end data source {$endDataSource->name} and origin data source {$originDataSource->name} failed with error code: {$resultCode}");
            }
        }

        if ($success) {
            Log::info("Migration configuration {$migrationConfiguration->name} completed successfully.");
        } else {
            Log::error("Migration configuration {$migrationConfiguration->name} failed.");
        }

        return $success;
    }
}
