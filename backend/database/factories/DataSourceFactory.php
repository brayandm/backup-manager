<?php

namespace Database\Factories;

use App\Entities\BackupDriverConfig;
use App\Entities\BackupDrivers\FileSystemDriver;
use App\Entities\ConnectionConfig;
use App\Entities\Connections\SshConnection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DataSourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(30),
            'connection_config' => new ConnectionConfig([
                new SshConnection(
                    'brayand',
                    'localhost',
                    '22',
                    'file',
                    '/home/brayand/.ssh/local',
                    'password'
                )]),
            'driver_config' => new BackupDriverConfig(
                new FileSystemDriver(
                    '/home/brayand/Storage/Personal/Capstone/Testing/Proyecto/DataImportante/'
                )
            ),
        ];
    }
}
