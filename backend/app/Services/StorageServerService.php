<?php

namespace App\Services;

use App\Casts\ConnectionCast;
use App\Casts\StorageServerDriverCast;
use App\Helpers\CommandBuilder;
use App\Models\StorageServer;
use Illuminate\Support\Facades\Log;

class StorageServerService
{
    public function getStorageServers($pagination, $page, $sort_by, $sort_order, $filters)
    {
        $query = StorageServer::query();

        foreach ($filters as $field) {
            $query->where($field['key'], $field['type'], $field['value'] ?? '');
        }

        $query->orderBy($sort_by, $sort_order);

        return $query->paginate($pagination, ['*'], 'page', $page);
    }

    public function getStorageServerNames()
    {
        return StorageServer::all()->map(function ($storageServer) {
            return [
                'id' => $storageServer->id,
                'name' => $storageServer->name,
            ];
        });
    }

    public function deleteStorageServer($id)
    {

        $storageServer = StorageServer::find($id);

        if ($storageServer === null) {
            throw new \Exception('Storage server not found.');
        }

        $storageServer->delete();

        return true;
    }

    public function deleteStorageServers($ids)
    {
        StorageServer::whereIn('id', $ids)->delete();

        return true;
    }

    public function deleteAllStorageServersExcept($ids)
    {
        StorageServer::whereNotIn('id', $ids)->delete();

        return true;
    }

    public function storeStorageServer($data)
    {
        $storageServer = new StorageServer();

        $storageServer->name = $data['name'];

        $connectionCast = app(ConnectionCast::class);
        $storageServer->connection_config = $connectionCast->get($storageServer, 'connection_config', $data['connection_config'], []);

        $storageServerDriverCast = app(StorageServerDriverCast::class);
        $storageServer->driver_config = $storageServerDriverCast->get($storageServer, 'driver_config', $data['driver_config'], []);

        $storageServer->save();

        return $storageServer;
    }

    public function updateStorageServer($id, $data)
    {
        $storageServer = StorageServer::find($id);

        if ($storageServer === null) {
            throw new \Exception('Storage server not found.');
        }

        $storageServer->name = $data['name'];

        $connectionCast = app(ConnectionCast::class);
        $storageServer->connection_config = $connectionCast->get($storageServer, 'connection_config', $data['connection_config'], []);

        $storageServerDriverCast = app(StorageServerDriverCast::class);
        $storageServer->driver_config = $storageServerDriverCast->get($storageServer, 'driver_config', $data['driver_config'], []);

        $storageServer->save();

        return $storageServer;
    }

    public function getStorageServer($id)
    {
        $storageServer = StorageServer::find($id);

        if ($storageServer === null) {
            throw new \Exception('Storage server not found.');
        }

        $connectionCast = app(ConnectionCast::class);
        $storageServerDriverCast = app(StorageServerDriverCast::class);

        return [
            'name' => $storageServer->name,
            'connection_config' => $connectionCast->set($storageServer, 'connection_config', $storageServer->connection_config, []),
            'driver_config' => $storageServerDriverCast->set($storageServer, 'driver_config', $storageServer->driver_config, []),
        ];
    }

    public function getStorageServerFreeSpace(StorageServer $storageServer)
    {
        $command = CommandBuilder::getStorageServerFreeSpace(
            $storageServer->connection_config,
            $storageServer->driver_config
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            Log::info('Storage server free space calculation succeeded');

            return $output[0];
        } else {
            Log::error("Storage server free space calculation failed with error code $resultCode");

            throw new \Exception('Storage server free space calculation failed.');
        }
    }
}
