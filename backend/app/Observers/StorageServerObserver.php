<?php

namespace App\Observers;

use App\Models\StorageServer;
use App\Services\StorageServerService;

class StorageServerObserver
{
    /**
     * Handle the StorageServer "created" event.
     */
    public function created(StorageServer $storageServer): void
    {
        $storageServerService = app(StorageServerService::class);

        $storageServer->total_space_free = $storageServerService->getStorageServerFreeSpace($storageServer);

        $storageServer->saveQuietly();
    }

    /**
     * Handle the StorageServer "updated" event.
     */
    public function updated(StorageServer $storageServer): void
    {
        $storageServerService = app(StorageServerService::class);

        $storageServer->total_space_free = $storageServerService->getStorageServerFreeSpace($storageServer);

        $storageServer->saveQuietly();
    }

    /**
     * Handle the StorageServer "deleted" event.
     */
    public function deleted(StorageServer $storageServer): void
    {
        //
    }

    /**
     * Handle the StorageServer "restored" event.
     */
    public function restored(StorageServer $storageServer): void
    {
        //
    }

    /**
     * Handle the StorageServer "force deleted" event.
     */
    public function forceDeleted(StorageServer $storageServer): void
    {
        //
    }
}
