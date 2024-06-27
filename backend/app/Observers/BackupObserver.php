<?php

namespace App\Observers;

use App\Models\Backup;

class BackupObserver
{
    /**
     * Handle the Backup "created" event.
     */
    public function created(Backup $backup): void
    {
        //
    }

    /**
     * Handle the Backup "updated" event.
     */
    public function updated(Backup $backup): void
    {
        //
    }

    /**
     * Handle the Backup "deleted" event.
     */
    public function deleted(Backup $backup): void
    {
        //
    }

    /**
     * Handle the Backup "restored" event.
     */
    public function restored(Backup $backup): void
    {
        //
    }

    /**
     * Handle the Backup "force deleted" event.
     */
    public function forceDeleted(Backup $backup): void
    {
        //
    }
}
