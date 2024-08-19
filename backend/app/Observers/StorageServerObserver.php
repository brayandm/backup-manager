<?php

namespace App\Observers;

use App\Jobs\CalculateFreeSpaceStorageServerJob;
use App\Jobs\CheckStorageServerAvailabilityJob;
use App\Models\StorageServer;

class StorageServerObserver
{
    /**
     * Handle the StorageServer "created" event.
     */
    public function created(StorageServer $storageServer): void
    {
        CalculateFreeSpaceStorageServerJob::dispatch($storageServer);

        CheckStorageServerAvailabilityJob::dispatch($storageServer);
    }

    /**
     * Handle the StorageServer "updated" event.
     */
    public function updated(StorageServer $storageServer): void
    {
        CheckStorageServerAvailabilityJob::dispatch($storageServer);
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
