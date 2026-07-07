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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('stock_code')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->decimal('price', 15, 2);
            $table->integer('kilometer');
            $table->string('color');
            $table->string('transmission'); // Manual, Automatic, CVT
            $table->string('fuel_type');    // Bensin, Diesel, Hybrid, Electric
            $table->integer('engine_cc')->nullable();
            $table->string('plate_number')->nullable();
            $table->string('condition');    // New, Excellent, Good, Fair, Poor
            $table->string('vin')->unique()->nullable();
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('status')->default('Available'); // Available, Reserved, Sold
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
