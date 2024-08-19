<?php

namespace App\Jobs;

use App\Enums\StorageServerStatus;
use App\Models\StorageServer;
use App\Services\StorageServerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckStorageServerAvailabilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private StorageServer $storageServer;

    /**
     * Create a new job instance.
     */
    public function __construct(StorageServer $storageServer)
    {
        $this->storageServer = $storageServer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $storageServerService = app(StorageServerService::class);

        $isAvailable = $storageServerService->isStorageServerAvailable($this->storageServer);

        $this->storageServer->status = $isAvailable ? StorageServerStatus::ACTIVE : StorageServerStatus::INACTIVE;

        $this->storageServer->saveQuietly();
    }
}
