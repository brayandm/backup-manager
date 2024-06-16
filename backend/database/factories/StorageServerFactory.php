<?php

namespace Database\Factories;

use App\Entities\ConnectionConfig;
use App\Entities\Connections\SshConnection;
use App\Entities\StorageServerDriverConfig;
use App\Entities\StorageServerDrivers\FileSystemDriver;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StorageServer>
 */
class StorageServerFactory extends Factory
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
            'driver_config' => new StorageServerDriverConfig(
                new FileSystemDriver(
                    '/home/brayand/Storage/Personal/Capstone/Testing/Proyecto/DataImportante/'
                )
            ),
        ];
    }
}
