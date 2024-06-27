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
        $backup->backupConfiguration->total_backups++;
        $backup->backupConfiguration->last_backup_at = $backup->created_at;
        $backup->backupConfiguration->save();

        $backup->backupConfiguration->storageServer->total_backups++;
        $backup->backupConfiguration->storageServer->save();
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
        $backup->backupConfiguration->total_backups--;
        $backup->backupConfiguration->save();

        $backup->backupConfiguration->storageServer->total_backups--;
        $backup->backupConfiguration->storageServer->save();
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
