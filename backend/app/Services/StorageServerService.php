<?php

namespace App\Services;

use App\Models\StorageServer;

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

    public function deleteStorageServer($id){

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
}
