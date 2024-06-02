<?php

namespace App\Console\Commands;

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

            $this->info("Backup {$backup->name} completed successfully.");
        } else {
            $this->error('Backup not found');
        }
    }
}
