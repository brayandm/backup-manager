<?php

namespace Database\Factories;

use App\Entities\CompressionMethodConfig;
use App\Entities\Methods\CompressionMethods\TarCompressionMethod;
use App\Models\DataSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BackupConfiguration>
 */
class MigrationConfigurationFactory extends Factory
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
            'data_source_id' => DataSource::factory(),
            'timezone' => 'UTC',
            'schedule_cron' => '0 0 * * *',
            'compression_config' => new CompressionMethodConfig(
                new TarCompressionMethod()
            ),
        ];
    }
}
