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
        Schema::create('weather_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->nullable()->constrained()->onDelete('set null');
            $table->date('log_date');
            $table->decimal('rainfall_mm', 6, 2)->nullable();
            $table->decimal('temperature_c', 5, 2)->nullable();
            $table->tinyInteger('humidity_pct')->nullable();
            $table->string('source')->default('openweather'); // or 'manual'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_logs');
    }
};
