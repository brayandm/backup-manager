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
        Schema::create('migration_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('data_source_id')->constrained();
            $table->string('timezone');
            $table->string('schedule_cron');
            $table->boolean('manual_migration')->default(false);
            $table->json('compression_config');
            $table->integer('total_migrations')->default(0);
            $table->dateTime('last_migration_at')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('migration_configurations');
    }
};
