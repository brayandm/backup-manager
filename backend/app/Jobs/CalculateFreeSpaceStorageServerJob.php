<?php

namespace App\Jobs;

use App\Models\StorageServer;
use App\Services\StorageServerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateFreeSpaceStorageServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private StorageServer $storageServer;

    /**
     * Maximum execution time in seconds
     */
    public $timeout = 10; // 10 seconds

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

        $this->storageServer->total_space_free = $storageServerService->getStorageServerFreeSpace($this->storageServer);

        $this->storageServer->saveQuietly();
    }
}
