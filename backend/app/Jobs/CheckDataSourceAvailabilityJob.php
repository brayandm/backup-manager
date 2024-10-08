<?php

namespace App\Jobs;

use App\Enums\DataSourceStatus;
use App\Models\DataSource;
use App\Services\DataSourceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckDataSourceAvailabilityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DataSource $dataSource;

    /**
     * Maximum execution time in seconds
     */
    public $timeout = 10; // 10 seconds

    /**
     * Create a new job instance.
     */
    public function __construct(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dataSourceService = app(DataSourceService::class);

        $isAvailable = $dataSourceService->isDataSourceAvailable($this->dataSource);

        $this->dataSource->status = $isAvailable ? DataSourceStatus::ACTIVE : DataSourceStatus::INACTIVE;

        $this->dataSource->saveQuietly();
    }
}
