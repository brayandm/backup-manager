<?php

namespace App\Jobs;

use App\Models\MigrationConfiguration;
use App\Services\MigrationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MigrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MigrationConfiguration $migrationConfiguration;

    /**
     * Create a new job instance.
     */
    public function __construct(MigrationConfiguration $migrationConfiguration)
    {
        $this->migrationConfiguration = $migrationConfiguration;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $migrationService = app(MigrationService::class);

        $migrationService->migrate($this->migrationConfiguration);
    }
}
