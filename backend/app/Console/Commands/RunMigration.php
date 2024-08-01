<?php

namespace App\Console\Commands;

use App\Models\MigrationConfiguration;
use App\Services\MigrationService;
use Illuminate\Console\Command;

class RunMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:run-migration-configuration {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a migration configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $migrationConfiguration = MigrationConfiguration::find($id);

        if ($migrationConfiguration) {
            $this->info("Running migration configuration: {$migrationConfiguration->name}");

            $migrationService = app(MigrationService::class);

            $success = $migrationService->migration($migrationConfiguration);

            if ($success) {
                $this->info("Migration configuration {$migrationConfiguration->name} completed successfully.");
            } else {
                $this->error("Migration configuration {$migrationConfiguration->name} failed.");
            }
        } else {
            $this->error('Migration configuration not found');
        }
    }
}
