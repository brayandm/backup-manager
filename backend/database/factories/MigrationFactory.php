<?php

namespace Database\Factories;

use App\Models\MigrationConfiguration;
use App\Models\DataSource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Backup>
 */
class MigrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'migration_configuration_id' => MigrationConfiguration::factory(),
            'origin_data_source_id' => DataSource::factory(),
            'end_data_source_id' => DataSource::factory(),
            'name' => 'migration-migration_configuration_name-origin_data_source_name-end_data_source_name-id'.rand(1, 1000).'-'.date('Ymd-His').'-UTC',
            'status' => rand(2, 3),
        ];
    }
}
