<?php

namespace App\Services;

use App\Models\Backup;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
use App\Models\Migration;
use App\Models\MigrationConfiguration;
use App\Models\StorageServer;
use Carbon\Carbon;

class AnalyticsService
{
    public function getOverview($timezone = null)
    {
        $timezone = $timezone ?? 'UTC';

        return [
            'week_backup_data' => $this->getBackupDataForLastWeek($timezone),
            'week_migration_data' => $this->getMigrationDataForLastWeek($timezone),
            'year_backup_data' => $this->getBackupDataForLastYear($timezone),
            'year_migration_data' => $this->getMigrationDataForLastYear($timezone),
            'storage_servers' => StorageServer::all()->map(function ($storageServer) {
                return [
                    'name' => $storageServer->name,
                    'used_space' => $storageServer->total_space_used,
                    'free_space' => $storageServer->total_space_free,
                    'type' => $storageServer->driver_config->driver->type,
                ];
            }),
            'summary_data' => [
                'total_storage_servers' => StorageServer::count(),
                'total_data_sources' => DataSource::count(),
                'total_backups' => Backup::count(),
                'total_migrations' => Migration::count(),
                'total_backup_configurations' => BackupConfiguration::count(),
                'total_migration_configurations' => MigrationConfiguration::count(),
                'total_space_used' => StorageServer::sum('total_space_used'),
            ],
        ];
    }

    private function getBackupDataForLastWeek($timezone)
    {
        $timeNow = Carbon::now();

        $backups = Backup::where('created_at', '>=', $timeNow->copy()->subDays(7))
            ->selectRaw("DATE(CONVERT_TZ(created_at, '+00:00', ?)) as date, COUNT(*) as count", [$timezone])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = $timeNow->copy()->setTimezone($timezone)->subDays(6)->startOfDay();

        $weekData = [];

        for ($i = 0; $i <= 6; $i++) {
            $weekData[$startDate->copy()->addDays($i)->format('Y-m-d')] = 0;
        }

        foreach ($backups as $backup) {
            $backupDate = Carbon::parse($backup->date)->format('Y-m-d');
            if (isset($weekData[$backupDate])) {
                $weekData[$backupDate] = $backup->count;
            }
        }

        return array_values($weekData);
    }

    private function getMigrationDataForLastWeek($timezone)
    {
        $timeNow = Carbon::now();

        $migrations = Migration::where('created_at', '>=', $timeNow->copy()->subDays(7))
            ->selectRaw("DATE(CONVERT_TZ(created_at, '+00:00', ?)) as date, COUNT(*) as count", [$timezone])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = $timeNow->copy()->setTimezone($timezone)->subDays(6)->startOfDay();

        $weekData = [];

        for ($i = 0; $i <= 6; $i++) {
            $weekData[$startDate->copy()->addDays($i)->format('Y-m-d')] = 0;
        }

        foreach ($migrations as $migration) {
            $migrationDate = Carbon::parse($migration->date)->format('Y-m-d');
            if (isset($weekData[$migrationDate])) {
                $weekData[$migrationDate] = $migration->count;
            }
        }

        return array_values($weekData);
    }

    private function getBackupDataForLastYear($timezone)
    {
        $timeNow = Carbon::now();

        $backups = Backup::where('created_at', '>=', $timeNow->copy()->subMonths(12))
            ->selectRaw("DATE_FORMAT(CONVERT_TZ(created_at, '+00:00', ?), '%Y-%m') as date, COUNT(*) as count", [$timezone])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = $timeNow->copy()->setTimezone($timezone)->subMonths(11)->startOfMonth();

        $monthData = [];

        for ($i = 0; $i < 12; $i++) {
            $monthData[$startDate->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        foreach ($backups as $backup) {
            $backupDate = Carbon::parse($backup->date)->format('Y-m');
            if (isset($monthData[$backupDate])) {
                $monthData[$backupDate] = $backup->count;
            }
        }

        return array_values($monthData);
    }

    private function getMigrationDataForLastYear($timezone)
    {
        $timeNow = Carbon::now();

        $migrations = Migration::where('created_at', '>=', $timeNow->copy()->subMonths(12))
            ->selectRaw("DATE_FORMAT(CONVERT_TZ(created_at, '+00:00', ?), '%Y-%m') as date, COUNT(*) as count", [$timezone])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = $timeNow->copy()->setTimezone($timezone)->subMonths(11)->startOfMonth();

        $monthData = [];

        for ($i = 0; $i < 12; $i++) {
            $monthData[$startDate->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        foreach ($migrations as $migration) {
            $migrationDate = Carbon::parse($migration->date)->format('Y-m');
            if (isset($monthData[$migrationDate])) {
                $monthData[$migrationDate] = $migration->count;
            }
        }

        return array_values($monthData);
    }
}
