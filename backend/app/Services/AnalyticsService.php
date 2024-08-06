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
    public function getOverview()
    {
        return [
            'week_backup_data' => $this->getBackupDataForLastWeek(),
            'week_migration_data' => $this->getMigrationDataForLastWeek(),
            'year_backup_data' => $this->getBackupDataForLastYear(),
            'year_migration_data' => $this->getMigrationDataForLastYear(),
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

    private function getBackupDataForLastWeek()
    {
        $backups = Backup::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $weekData = array_fill(0, 7, 0);

        foreach ($backups as $backup) {
            $dateDiff = Carbon::now()->diffInDays(Carbon::parse($backup->date));
            if ($dateDiff < 7) {
                $weekData[6 - $dateDiff] = $backup->count;
            }
        }

        return $weekData;
    }

    private function getMigrationDataForLastWeek()
    {
        $migrations = Migration::where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $weekData = array_fill(0, 7, 0);

        foreach ($migrations as $migration) {
            $dateDiff = Carbon::now()->diffInDays(Carbon::parse($migration->date));
            if ($dateDiff < 7) {
                $weekData[6 - $dateDiff] = $migration->count;
            }
        }

        return $weekData;
    }

    private function getBackupDataForLastYear()
    {
        $backups = Backup::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthData = array_fill(0, 12, 0);

        foreach ($backups as $backup) {
            $dateDiff = Carbon::now()->diffInMonths(Carbon::create($backup->year, $backup->month));
            if ($dateDiff < 12) {
                $monthData[11 - $dateDiff] = $backup->count;
            }
        }

        return $monthData;
    }

    private function getMigrationDataForLastYear()
    {
        $migrations = Migration::where('created_at', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthData = array_fill(0, 12, 0);

        foreach ($migrations as $migration) {
            $dateDiff = Carbon::now()->diffInMonths(Carbon::create($migration->year, $migration->month));
            if ($dateDiff < 12) {
                $monthData[11 - $dateDiff] = $migration->count;
            }
        }

        return $monthData;
    }
}
