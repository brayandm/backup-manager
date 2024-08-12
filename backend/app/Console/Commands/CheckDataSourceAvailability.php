<?php

namespace App\Console\Commands;

use App\Models\DataSource;
use App\Services\DataSourceService;
use Illuminate\Console\Command;

class CheckDataSourceAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-data-source-availability {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check data source availability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $dataSource = DataSource::find($id);

        if ($dataSource) {
            $this->info("Checking data source availability: {$dataSource->name}");

            $dataSourceService = app(DataSourceService::class);

            $success = $dataSourceService->isDataSourceAvailable($dataSource);

            if ($success) {
                $this->info("Data source {$dataSource->name} is available.");
            } else {
                $this->error("Data source {$dataSource->name} is not available.");
            }
        } else {
            $this->error('Data source not found');
        }
    }
}
