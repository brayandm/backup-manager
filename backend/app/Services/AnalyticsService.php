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
            'week_backup_data' => [1, 2],
            'week_migration_data' => [1, 2],
            'month_backup_data' => [3, 4, 6],
            'month_migration_data' => [4, 3, 4],
            'storage_servers' => [
                [
                    'name' => 'Storage Server 1',
                    'used_space' => 100,
                    'free_space' => 200,
                ],
                [
                    'name' => 'Storage Server 2',
                    'used_space' => 200,
                    'free_space' => 100,
                ],
            ],
            'summary_data' => [
                'total_storage_servers' => StorageServer::count(),
                'total_data_sources' => DataSource::count(),
                'total_backups' => Backup::count(),
                'total_migrations' => Migration::count(),
                'total_backup_configurations' => BackupConfiguration::count(),
                'total_migration_configurations' => MigrationConfiguration::count(),
            ],
        ];
    }
}
