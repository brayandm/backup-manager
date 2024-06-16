<?php

namespace Database\Factories;

use App\Entities\CompressionMethodConfig;
use App\Entities\ConnectionConfig;
use App\Entities\Connections\SshConnection;
use App\Entities\EncryptionMethodConfig;
use App\Entities\IntegrityCheckMethodConfig;
use App\Entities\Methods\CompressionMethods\TarCompressionMethod;
use App\Entities\Methods\EncryptionMethods\Aes256CbcEncryptionMethod;
use App\Entities\Methods\IntegrityCheckMethods\Sha256SumIntegrityCheckMethod;
use App\Entities\StorageServerDriverConfig;
use App\Entities\StorageServerDrivers\FileSystemDriver;
use App\Models\BackupConfiguration;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_configuration_id')->constrained();
            $table->string('name');
            $table->json('connection_config');
            $table->json('driver_config');
            $table->json('compression_config');
            $table->json('encryption_config');
            $table->json('integrity_check_config');
            $table->integer('status')->default(0);
            $table->timestamps();
        });
*/

// $backup->name = 'backup-'.$this->formatText($backupConfiguration->name).'-'.$this->formatText($storageServer->name).'-'.'id'.$backup->id.'-'.date('Ymd-His').'-UTC';

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Backup>
 */
class BackupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'backup_configuration_id' => BackupConfiguration::factory(),
            'name' => 'backup-backup_configuration_name-storage_server_name-id'.rand(1, 1000).'-'.date('Ymd-His').'-UTC',
            'connection_config' => new ConnectionConfig([
                new SshConnection(
                    'brayand',
                    'localhost',
                    '22',
                    'file',
                    '/home/brayand/.ssh/local',
                    null
                )]),
            'driver_config' => new StorageServerDriverConfig(
                new FileSystemDriver(
                    '/home/brayand/Storage/Personal/Capstone/Testing/Proyecto/DataImportante/'
                )
            ),
            'compression_config' => new CompressionMethodConfig(
                new TarCompressionMethod()
            ),
            'encryption_config' => new EncryptionMethodConfig(
                new Aes256CbcEncryptionMethod(null)
            ),
            'integrity_check_config' => new IntegrityCheckMethodConfig(
                new Sha256SumIntegrityCheckMethod(null)
            ),
            'status' => rand(2, 3),
        ];
    }
}
