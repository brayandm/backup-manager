<?php

namespace App\Jobs;

use App\Models\BackupConfiguration;
use App\Services\BackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RetentionPolicyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private BackupConfiguration $backupConfiguration;

    /**
     * Create a new job instance.
     */
    public function __construct(BackupConfiguration $backupConfiguration)
    {
        $this->backupConfiguration = $backupConfiguration;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $backupService = app(BackupService::class);

        $backupService->retentionPolicy($this->backupConfiguration);
    }
}
