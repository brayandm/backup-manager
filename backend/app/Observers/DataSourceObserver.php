<?php

namespace App\Observers;

use App\Jobs\CheckDataSourceAvailabilityJob;
use App\Models\DataSource;

class DataSourceObserver
{
    /**
     * Handle the DataSource "created" event.
     */
    public function created(DataSource $dataSource): void
    {
        new CheckDataSourceAvailabilityJob($dataSource);
    }

    /**
     * Handle the DataSource "updated" event.
     */
    public function updated(DataSource $dataSource): void
    {
        new CheckDataSourceAvailabilityJob($dataSource);
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
