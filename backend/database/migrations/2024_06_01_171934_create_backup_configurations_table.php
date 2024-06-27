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
        Schema::create('backup_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('connection_config');
            $table->json('driver_config');
            $table->string('schedule_cron');
            $table->json('retention_policy_config');
            $table->json('compression_config');
            $table->json('encryption_config');
            $table->json('integrity_check_config');
            $table->integer('total_backups')->default(0);
            $table->bigInteger('total_size')->default(0);
            $table->dateTime('last_backup_at')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_configurations');
    }
};
