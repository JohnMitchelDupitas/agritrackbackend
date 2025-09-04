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
        Schema::create('crop_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->cascadeOnDelete();
            $table->string('crop_type');
            $table->string('variety')->nullable();
            $table->string('season_label')->nullable();
            $table->date('date_planted');
            $table->date('date_harvested')->nullable();
            $table->decimal('yield_kg', 10, 2)->nullable();
            $table->enum('status', ['planned', 'planted', 'harvested'])->default('planted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_cycles');
    }
};
