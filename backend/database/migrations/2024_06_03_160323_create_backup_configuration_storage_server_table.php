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
        Schema::create('backup_configuration_storage_server', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_configuration_id')->constrained()->onDelete('cascade')->name('bc_ss_backup_configuration_id_fk');
            $table->foreignId('storage_server_id')->constrained()->onDelete('cascade')->name('bc_ss_storage_server_id_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_configuration_storage_server');
    }
};
