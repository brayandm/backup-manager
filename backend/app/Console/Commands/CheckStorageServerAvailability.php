<?php

namespace App\Console\Commands;

use App\Models\StorageServer;
use App\Services\StorageServerService;
use Illuminate\Console\Command;

class CheckStorageServerAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-storage-server-availability {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Storage Server availability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $StorageServer = StorageServer::find($id);

        if ($StorageServer) {
            $this->info("Checking Storage Server availability: {$StorageServer->name}");

            $StorageServerService = app(StorageServerService::class);

            $success = $StorageServerService->isStorageServerAvailable($StorageServer);

            if ($success) {
                $this->info("Storage Server {$StorageServer->name} is available.");
            } else {
                $this->error("Storage Server {$StorageServer->name} is not available.");
            }
        } else {
            $this->error('Storage Server not found');
        }
    }
}
