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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('crop_cycle_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['pest', 'disease', 'flood', 'drought', 'typhoon', 'other']);
            $table->enum('severity', ['low', 'medium', 'high'])->nullable();
            $table->tinyInteger('impact_pct')->nullable(); // 0–100 %
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->date('date_reported');
            $table->decimal('gps_lat', 10, 7)->nullable();
            $table->decimal('gps_lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
