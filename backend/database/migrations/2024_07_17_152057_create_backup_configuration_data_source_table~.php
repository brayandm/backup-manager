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
        Schema::create('backup_configuration_data_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_configuration_id')->constrained()->name('bc_ds_backup_configuration_id_fk');
            $table->foreignId('data_source_id')->constrained()->name('bc_ds_data_source_id_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_configuration_data_source');
    }
};
