<?php

namespace App\Services;

use App\Jobs\BackupJob;
use App\Models\BackupConfiguration;

class BackupService
{
    public function Backup(BackupConfiguration $backupConfiguration)
    {
        BackupJob::dispatch($backupConfiguration);
    }
}
