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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_configuration_id')->constrained();
            $table->foreignId('storage_server_id')->constrained();
            $table->string('name');
            $table->json('driver_config');
            $table->json('compression_config');
            $table->json('encryption_config');
            $table->json('integrity_check_config');
            $table->bigInteger('size')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
