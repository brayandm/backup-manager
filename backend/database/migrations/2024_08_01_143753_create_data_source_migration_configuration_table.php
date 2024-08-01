<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_source_migration_configuration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('migration_configuration_id')->constrained()->name('ds_mc_migration_configuration_id_fk');
            $table->foreignId('data_source_id')->constrained()->name('ds_mc_data_source_id_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_source_migration_configuration');
    }
};
