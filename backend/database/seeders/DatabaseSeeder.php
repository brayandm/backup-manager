<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Backup;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
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
        $this->command->info('Creating user');
        User::factory()->create(
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]
        );

        // Backup configuration factory
        $this->command->info('Creating backup configurations');
        BackupConfiguration::factory(20)->create();

        $backupConfiguration = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration',
                'schedule_cron' => '* * * * *',
            ]
        );

        // Data source factory
        $this->command->info('Creating data sources');
        DataSource::factory(20)->create();

        $dataSource = DataSource::factory()->create(
            [
                'name' => 'Data Source',
            ]
        );

        // Storage server factory
        $this->command->info('Creating storage servers');
        StorageServer::factory(20)->create();

        $storageServer = StorageServer::factory()->create(
            [
                'name' => 'Storage Server',
            ]
        );

        $backupConfiguration->storageServers()->attach($storageServer);
        $backupConfiguration->dataSources()->attach($dataSource);

        // Backup factory
        $this->command->info('Creating backups');
        Backup::factory(49)->create([
            'data_source_id' => $dataSource->id,
            'storage_server_id' => $storageServer->id,
        ]);

        app(BackupService::class)->backup($backupConfiguration);
    }
}
