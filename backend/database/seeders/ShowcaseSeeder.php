<?php

namespace Database\Seeders;

use App\Entities\ConnectionConfig;
use App\Entities\Connections\DockerConnection;
use App\Entities\Connections\SshConnection;
use App\Entities\DataSourceDriverConfig;
use App\Entities\DataSourceDrivers\FileSystemDriver as DataSourceDriversFileSystemDriver;
use App\Entities\DataSourceDrivers\MysqlDriver;
use App\Entities\StorageServerDriverConfig;
use App\Entities\StorageServerDrivers\FileSystemDriver;
use App\Models\BackupConfiguration;
use App\Models\DataSource;
use App\Models\MigrationConfiguration;
use App\Models\StorageServer;
use App\Models\User;
use App\Services\BackupService;
use App\Services\MigrationService;
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

        $backupConfiguration4 = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 4',
                'schedule_cron' => '*/2 * * * *',

            ]
        );

        $backupConfiguration5 = BackupConfiguration::factory()->create(
            [
                'name' => 'Backup Configuration 5',
                'schedule_cron' => '*/2 * * * *',

            ]
        );

        // Data source factory
        $this->command->info('Creating data sources');
        $dataSource1 = DataSource::factory()->create(
            [
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ), ]),
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
                'driver_config' => new DataSourceDriverConfig(
                    new DataSourceDriversFileSystemDriver(
                        '/data/'
                    )
                ),
            ]
        );

        $dataSource3 = DataSource::factory()->create(
            [
                'name' => 'Docker Container',
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new DockerConnection('frontend-frontend-1')]),
                'driver_config' => new DataSourceDriverConfig(
                    new DataSourceDriversFileSystemDriver(
                        '/app/public'
                    )
                ),
            ]
        );

        $dataSource4 = DataSource::factory()->create(
            [
                'name' => 'Mysql Database',
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ),
                    new DockerConnection('backend-laravel.test-1')]),
                'driver_config' => new DataSourceDriverConfig(
                    new MysqlDriver(
                        'mysql',
                        '3306',
                        'root',
                        'password',
                        'example_app'
                    )
                ),
            ]
        );

        $dataSource5 = DataSource::factory()->create(
            [
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ), ]),
                'name' => 'Data Source 5',
                'driver_config' => new DataSourceDriverConfig(
                    new DataSourceDriversFileSystemDriver(
                        '/home/brayand/Storage/Personal/Projects/Capstone/Testing/Proyecto/DataImportante2/'
                    )
                ),
            ]
        );

        $dataSource6 = DataSource::factory()->create(
            [
                'connection_config' => new ConnectionConfig([
                    new SshConnection(
                        'brayand',
                        'localhost',
                        '22',
                        'file',
                        '/home/brayand/.ssh/local',
                        'password'
                    ), ]),
                'name' => 'Data Source 6',
                'driver_config' => new DataSourceDriverConfig(
                    new DataSourceDriversFileSystemDriver(
                        '/home/brayand/Storage/Personal/Projects/Capstone/Testing/Proyecto/DataImportante3/'
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
                        '/home/brayand/Storage/Personal/Projects/Capstone/Testing/Server/'
                    )
                ),
            ]
        );

        $storageServer2 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 2',
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/home/brayand/Storage/Personal/Projects/Capstone/Testing/Server2/'
                    )
                ),
            ]
        );

        $storageServer3 = StorageServer::factory()->create(
            [
                'name' => 'Storage Server 3',
                'driver_config' => new StorageServerDriverConfig(
                    new FileSystemDriver(
                        '/home/brayand/Storage/Personal/Projects/Capstone/Testing/Server3/'
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

        // Migration configuration factory
        $this->command->info('Creating migration configurations');
        $migrationConfiguration1 = MigrationConfiguration::factory()->create(
            [
                'name' => 'Migration Configuration 1',
                'data_source_id' => $dataSource1->id,
                'schedule_cron' => '* * * * *',
            ]
        );

        // Attach data sources to backup configurations
        $this->command->info('Attaching data sources to backup configurations');

        $backupConfiguration1->dataSources()->attach($dataSource1);

        $backupConfiguration2->dataSources()->attach($dataSource1);

        $backupConfiguration3->dataSources()->attach($dataSource2);

        $backupConfiguration4->dataSources()->attach($dataSource3);

        $backupConfiguration5->dataSources()->attach($dataSource4);

        // Attach storage servers to backup configurations
        $this->command->info('Attaching storage servers to backup configurations');
        $backupConfiguration1->storageServers()->attach($storageServer1);
        $backupConfiguration1->storageServers()->attach($storageServer2);

        $backupConfiguration2->storageServers()->attach($storageServer2);
        $backupConfiguration2->storageServers()->attach($storageServer4);

        $backupConfiguration3->storageServers()->attach($storageServer3);

        $backupConfiguration4->storageServers()->attach($storageServer3);

        $backupConfiguration5->storageServers()->attach($storageServer3);

        // Attach data sources to migration configurations
        $this->command->info('Attaching data sources to migration configurations');
        $migrationConfiguration1->dataSources()->attach($dataSource5);
        $migrationConfiguration1->dataSources()->attach($dataSource6);

        // Migration factory

        $this->command->info('Running migration configuration 1');
        app(MigrationService::class)->migrate($migrationConfiguration1);

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
        app(BackupService::class)->backup($backupConfiguration4);
        $this->command->info('Backup Configuration 4 backed up');
        app(BackupService::class)->backup($backupConfiguration5);
        $this->command->info('Backup Configuration 5 backed up');
    }
}
