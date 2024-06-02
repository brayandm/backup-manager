<?php

namespace App\Console\Commands;

use App\Helpers\CommandBuilder;
use App\Models\Backup;
use Illuminate\Console\Command;

class RunBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-backup {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $backup = Backup::find($id);

        if ($backup) {
            $this->info("Running backup: {$backup->name}");

            $command = CommandBuilder::Backup(
                $id,
                $backup->connection_config,
                $backup->driver_config,
                $backup->storageServer->connection_config,
                $backup->storageServer->driver_config);

            $output = null;
            $resultCode = null;
            exec($command, $output, $resultCode);

            if ($resultCode === 0) {
                $this->info("Backup {$backup->name} completed successfully.");
            } else {
                $this->error("Backup {$backup->name} failed with error code: {$resultCode}");
            }
        } else {
            $this->error('Backup not found');
        }
    }
}
