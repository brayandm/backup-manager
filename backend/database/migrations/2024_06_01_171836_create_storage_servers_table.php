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
        Schema::create('storage_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('connection_config');
            $table->json('driver_config');
            $table->integer('total_backups')->default(0);
            $table->bigInteger('total_space_used')->default(0);
            $table->bigInteger('total_space_free')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_servers');
    }
};
