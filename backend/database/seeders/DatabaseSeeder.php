<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Backup;
use App\Models\BackupConfiguration;
use App\Models\StorageServer;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User factory
        User::factory()->create(
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]
        );

        // Backup configuration factory
        $backupConfiguration = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 1',
                'schedule_cron' => '* * * * *',
            ]
        );

        BackupConfiguration::factory(20)->create();

        // Storage server factory
        $storageServer = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 1',
            ]
        );

        StorageServer::factory(20)->create();

        $backupConfiguration->storageServers()->attach($storageServer);

        // Backup factory
        app(BackupService::class)->backup($backupConfiguration);

        Backup::factory(49)->create([
            'backup_configuration_id' => $backupConfiguration->id,
            'storage_server_id' => $storageServer->id,
        ]);
    }
}
