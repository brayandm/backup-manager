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
        $backups = Backup::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = Carbon::now($timezone)->subDays(6)->startOfDay();

        $weekData = [];

        for ($i = 0; $i <= 6; $i++) {
            $weekData[$startDate->copy()->addDays($i)->format('Y-m-d')] = 0;
        }

        foreach ($backups as $backup) {
            $backupDate = Carbon::parse($backup->date, $timezone)->format('Y-m-d');
            if (isset($weekData[$backupDate])) {
                $weekData[$backupDate] = $backup->count;
            }
        }

        return array_values($weekData);
    }

    private function getMigrationDataForLastWeek($currentTime)
    {
        $migrations = Migration::where('created_at', '>=', $currentTime->copy()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $startDate = $currentTime->copy()->subDays(6)->startOfDay();

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

    private function getBackupDataForLastYear($currentTime)
    {
        $backups = Backup::where('created_at', '>=', $currentTime->copy()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $startDate = $currentTime->copy()->subMonths(11)->startOfMonth();
        $monthData = [];

        for ($i = 0; $i < 12; $i++) {
            $monthData[$startDate->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        foreach ($backups as $backup) {
            $backupDate = Carbon::create($backup->year, $backup->month)->format('Y-m');
            if (isset($monthData[$backupDate])) {
                $monthData[$backupDate] = $backup->count;
            }
        }

        return array_values($monthData);
    }

    private function getMigrationDataForLastYear($currentTime)
    {
        $migrations = Migration::where('created_at', '>=', $currentTime->copy()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $startDate = $currentTime->copy()->subMonths(11)->startOfMonth();
        $monthData = [];

        for ($i = 0; $i < 12; $i++) {
            $monthData[$startDate->copy()->addMonths($i)->format('Y-m')] = 0;
        }

        foreach ($migrations as $migration) {
            $migrationDate = Carbon::create($migration->year, $migration->month)->format('Y-m');
            if (isset($monthData[$migrationDate])) {
                $monthData[$migrationDate] = $migration->count;
            }
        }

        return array_values($monthData);
    }
}
