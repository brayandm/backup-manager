<?php

namespace App\Services;

use App\Casts\ConnectionCast;
use App\Casts\DataSourceDriverCast;
use App\Enums\DataSourceStatus;
use App\Helpers\CommandBuilder;
use App\Models\DataSource;
use Illuminate\Support\Facades\Log;

class DataSourceService
{
    public function getDataSources($pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = DataSource::query();

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function getDataSourceNames()
    {
        return DataSource::all()->map(function ($dataSource) {
            return [
                'id' => $dataSource->id,
                'name' => $dataSource->name,
            ];
        });
    }

    public function getMigrationCompatible($id)
    {
        $dataSource = DataSource::find($id);

        $compatibilityGroups = [
            'files_system' => ['files_system', 'aws_s3'],
            'aws_s3' => ['files_system', 'aws_s3'],
            'mysql' => ['mysql'],
            'pgsql' => ['pgsql'],
        ];

        $compatibleDrivers = $compatibilityGroups[$dataSource->driver_config->driver->type] ?? [];

        $dataSources = DataSource::all()->filter(function ($dataSource) use ($compatibleDrivers) {
            return in_array($dataSource->driver_config->driver->type, $compatibleDrivers);
        });

        return $dataSources->map(function ($dataSource) {
            return [
                'id' => $dataSource->id,
                'name' => $dataSource->name,
            ];
        });
    }

    public function deleteDataSource($id)
    {

        $dataSource = DataSource::find($id);

        if ($dataSource === null) {
            throw new \Exception('Data source not found.');
        }

        $dataSource->delete();

        return true;
    }

    public function deleteDataSources($ids)
    {
        DataSource::whereIn('id', $ids)->delete();

        return true;
    }

    public function deleteAllDataSourcesExcept($ids)
    {
        DataSource::whereNotIn('id', $ids)->delete();

        return true;
    }

    public function storeDataSource($data)
    {
        $dataSource = new DataSource();

        $dataSource->name = $data['name'];

        $connectionCast = app(ConnectionCast::class);
        $dataSource->connection_config = $connectionCast->get($dataSource, 'connection_config', $data['connection_config'], []);

        $dataSourceDriverCast = app(DataSourceDriverCast::class);
        $dataSource->driver_config = $dataSourceDriverCast->get($dataSource, 'driver_config', $data['driver_config'], []);

        $dataSource->save();

        return $dataSource;
    }

    public function updateDataSource($id, $data)
    {
        $dataSource = DataSource::find($id);

        if ($dataSource === null) {
            throw new \Exception('Data source not found.');
        }

        $dataSource->name = $data['name'];

        $connectionCast = app(ConnectionCast::class);
        $dataSource->connection_config = $connectionCast->get($dataSource, 'connection_config', $data['connection_config'], []);

        $dataSourceDriverCast = app(DataSourceDriverCast::class);
        $dataSource->driver_config = $dataSourceDriverCast->get($dataSource, 'driver_config', $data['driver_config'], []);

        $dataSource->save();

        return $dataSource;
    }

    public function getDataSource($id)
    {
        $dataSource = DataSource::find($id);

        if ($dataSource === null) {
            throw new \Exception('Data source not found.');
        }

        $connectionCast = app(ConnectionCast::class);
        $dataSourceDriverCast = app(DataSourceDriverCast::class);

        return [
            'name' => $dataSource->name,
            'connection_config' => $connectionCast->set($dataSource, 'connection_config', $dataSource->connection_config, []),
            'driver_config' => $dataSourceDriverCast->set($dataSource, 'driver_config', $dataSource->driver_config, []),
        ];
    }

    public function isDataSourceAvailable(DataSource $dataSource)
    {
        $command = CommandBuilder::isDataSourceAvailable(
            $dataSource->connection_config,
            $dataSource->driver_config
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0 && count($output) > 0 && $output[0] === 'true') {
            Log::info('Data source is available');

            return true;
        } else {
            Log::error("Data source is not available with error code $resultCode");

            return false;
        }
    }

    public function refreshDataSourceStatus(DataSource $dataSource)
    {
        $isAvailable = $this->isDataSourceAvailable($dataSource);

        $dataSource->status = $isAvailable ? DataSourceStatus::ACTIVE : DataSourceStatus::INACTIVE;
        $dataSource->save();

        return $dataSource;
    }
}
