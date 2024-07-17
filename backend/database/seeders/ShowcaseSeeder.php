<?php

namespace Database\Seeders;

use App\Entities\BackupDriverConfig;
use App\Entities\BackupDrivers\FileSystemDriver as BackupDriversFileSystemDriver;
use App\Entities\ConnectionConfig;
use App\Entities\Connections\SshConnection;
use App\Entities\StorageServerDriverConfig;
use App\Entities\StorageServerDrivers\FileSystemDriver;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
use App\Models\StorageServer;
use App\Models\User;
use App\Services\BackupService;
use Illuminate\Database\Seeder;

class ShowcaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        $backupConfiguration1 = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 1',
                'schedule_cron' => '* * * * *',
            ]
        );

        $backupConfiguration2 = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 2',
                'schedule_cron' => '*/2 * * * *',
            ]
        );

        $backupConfiguration3 = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 3',
                'schedule_cron' => '*/2 * * * *',

            ]
        );

        // Data source factory
        $this->command->info('Creating data sources');
        $dataSource1 = DataSource::factory()->create(
            [
                'name' => 'Data Source 1',
            ]
        );

        $dataSource2 = DataSource::factory()->create(
            [
                'name' => 'Data Source 2',
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'root',
                        '95.85.52.6',
                        '22',
                        'file',
                        '/home/brayand/.ssh/loc_hs_course',
                        'password'
                    )]),
                'driver_config' => new BackupDriverConfig(
                    new BackupDriversFileSystemDriver(
                        '/data/'
                    )
                ),
            ]
        );

        // Storage server factory
        $this->command->info('Creating storage servers');
        $storageServer1 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 1',
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/home/brayand/Storage/Personal/Capstone/Testing/Server/'
                    )
                ),
            ]
        );

        $storageServer2 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 2',
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/home/brayand/Storage/Personal/Capstone/Testing/Server2/'
                    )
                ),
            ]
        );

        $storageServer3 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 3',
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/home/brayand/Storage/Personal/Capstone/Testing/Server3/'
                    )
                ),
            ]
        );

        $storageServer4 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server External',
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'root',
                        '95.85.52.6',
                        '22',
                        'file',
                        '/home/brayand/.ssh/loc_hs_course',
                        'password'
                    )]),
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/storage/'
                    )
                ),
            ]
        );

        $this->command->info('Attaching data sources to backup configurations');

        $backupConfiguration1->dataSources()->attach($dataSource1);

        $backupConfiguration2->dataSources()->attach($dataSource1);

        $backupConfiguration3->dataSources()->attach($dataSource2);

        $this->command->info('Attaching storage servers to backup configurations');
        $backupConfiguration1->storageServers()->attach($storageServer1);
        $backupConfiguration1->storageServers()->attach($storageServer2);

        $backupConfiguration2->storageServers()->attach($storageServer2);
        $backupConfiguration2->storageServers()->attach($storageServer4);

        $backupConfiguration3->storageServers()->attach($storageServer3);

        // Backup factory
        $this->command->info('Creating backups');
        app(BackupService::class)->backup($backupConfiguration1);
        $this->command->info('Backup Configuration 1 backed up');
        app(BackupService::class)->backup($backupConfiguration1);
        $this->command->info('Backup Configuration 1 backed up');
        app(BackupService::class)->backup($backupConfiguration2);
        $this->command->info('Backup Configuration 2 backed up');
        app(BackupService::class)->backup($backupConfiguration3);
        $this->command->info('Backup Configuration 3 backed up');
    }
}
