<?php

namespace App\Services;

use App\Casts\CompressionMethodCast;
use App\Models\MigrationConfiguration;

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
        return true;
    }
}
