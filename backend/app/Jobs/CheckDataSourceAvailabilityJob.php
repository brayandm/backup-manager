<?php

namespace App\Jobs;

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

        $dataSourceService->isDataSourceAvailable($this->dataSource);
    }
}
