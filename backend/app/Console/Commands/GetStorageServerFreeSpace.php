<?php

namespace App\Console\Commands;

use App\Models\StorageServer;
use App\Services\StorageServerService;
use Illuminate\Console\Command;

class GetStorageServerFreeSpace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-storage-server-free-space {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the free space of a storage server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $storageServer = StorageServer::find($id);

        if ($storageServer) {
            $this->info("Getting free space of storage server: {$storageServer->name}");

            $storageServerService = app(StorageServerService::class);

            $freeSpace = $storageServerService->getStorageServerFreeSpace($storageServer);

            if ($freeSpace) {
                $this->info("Free space of storage server {$storageServer->name}: {$freeSpace}");
            } else {
                $this->error("Failed to get free space of storage server {$storageServer->name}");
            }
        } else {
            $this->error('Storage server not found');
        }
    }
}
