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

        $backup->storageServer->total_backups++;
        $backup->storageServer->save();
    }

    /**
     * Handle the Backup "updated" event.
     */
    public function updated(Backup $backup): void
    {
        if ($backup->getOriginal('size') === null && $backup->size !== null) {
            $backup->backupConfiguration->total_size += $backup->size;
            $backup->backupConfiguration->save();

            $backup->storageServer->total_space_used += $backup->size;
            $backup->storageServer->total_space_free -= $backup->size;
            $backup->storageServer->save();
        }
    }

    /**
     * Handle the Backup "deleted" event.
     */
    public function deleted(Backup $backup): void
    {
        $backup->backupConfiguration->total_backups--;
        $backup->backupConfiguration->total_size -= $backup->size;
        $backup->backupConfiguration->save();

        $backup->storageServer->total_backups--;
        $backup->storageServer->total_space_used -= $backup->size;
        $backup->storageServer->total_space_free += $backup->size;
        $backup->storageServer->save();
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
