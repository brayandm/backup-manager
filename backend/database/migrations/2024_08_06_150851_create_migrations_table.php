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
            $table->foreignId('migration_configuration_id')->constrained();
            $table->foreignId('origin_data_source_id')->constrained('data_sources');
            $table->foreignId('end_data_source_id')->constrained('data_sources');
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
        Schema::dropIfExists('migrations');
    }
};
