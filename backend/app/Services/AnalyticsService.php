<?php

namespace App\Services;

use App\Models\Backup;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
use App\Models\Migration;
use App\Models\MigrationConfiguration;
use App\Models\StorageServer;

class AnalyticsService
{
    public function getOverview()
    {
        return [
            'summary_data' => [
                'total_storage_servers' => StorageServer::count(),
                'total_data_sources' => DataSource::count(),
                'total_backups' => Backup::count(),
                'total_migrations' => Migration::count(),
                'total_backup_configurations' => BackupConfiguration::count(),
                'total_migration_configurations' => MigrationConfiguration::count(),
            ]
        ];
    }
}
