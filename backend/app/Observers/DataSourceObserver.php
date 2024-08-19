<?php

namespace App\Observers;

use App\Enums\DataSourceStatus;
use App\Models\DataSource;
use App\Services\DataSourceService;

class DataSourceObserver
{
    /**
     * Handle the DataSource "created" event.
     */
    public function created(DataSource $dataSource): void
    {
        $dataSourceService = app(DataSourceService::class);

        $dataSource->status = $dataSourceService->isDataSourceAvailable($dataSource)
            ? DataSourceStatus::ACTIVE
            : DataSourceStatus::INACTIVE;

        $dataSource->saveQuietly();
    }

    /**
     * Handle the DataSource "updated" event.
     */
    public function updated(DataSource $dataSource): void
    {
        //
    }

    /**
     * Handle the DataSource "deleted" event.
     */
    public function deleted(DataSource $dataSource): void
    {
        //
    }

    /**
     * Handle the DataSource "restored" event.
     */
    public function restored(DataSource $dataSource): void
    {
        //
    }

    /**
     * Handle the DataSource "force deleted" event.
     */
    public function forceDeleted(DataSource $dataSource): void
    {
        //
    }
}
