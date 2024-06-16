<?php

namespace Database\Factories;

use App\Entities\BackupDriverConfig;
use App\Entities\BackupDrivers\FileSystemDriver;
use App\Entities\CompressionMethodConfig;
use App\Entities\ConnectionConfig;
use App\Entities\Connections\SshConnection;
use App\Entities\EncryptionMethodConfig;
use App\Entities\IntegrityCheckMethodConfig;
use App\Entities\Methods\CompressionMethods\TarCompressionMethod;
use App\Entities\Methods\EncryptionMethods\Aes256CbcEncryptionMethod;
use App\Entities\Methods\IntegrityCheckMethods\Sha256SumIntegrityCheckMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BackupConfiguration>
 */
class BackupConfigurationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text($maxNbChars = 30),
            'connection_config' => new ConnectionConfig([
                new SshConnection(
                    'brayand',
                    'localhost',
                    '22',
                    'file',
                    '/home/brayand/.ssh/local',
                    null
                )]),
            'driver_config' => new BackupDriverConfig(
                new FileSystemDriver(
                    '/home/brayand/Storage/Personal/Capstone/Testing/Proyecto/DataImportante/'
                )
            ),
            'schedule_cron' => '0 0 * * *',
            'retention_policy_config' => '{}',
            'compression_config' => new CompressionMethodConfig(
                new TarCompressionMethod()
            ),
            'encryption_config' => new EncryptionMethodConfig(
                new Aes256CbcEncryptionMethod(null)
            ),
            'integrity_check_config' => new IntegrityCheckMethodConfig(
                new Sha256SumIntegrityCheckMethod(null)
            ),
        ];
    }
}
