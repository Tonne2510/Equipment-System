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
        Schema::create('equipment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Máy tính, Máy chiếu, Pin sạc, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('equipment_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Apple, Dell, Canon, etc.
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('equipment_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('equipment_categories')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('equipment_brands')->onDelete('cascade');
            $table->string('name'); // MacBook Pro, Canon EOS 5D, etc.
            $table->text('specifications')->nullable(); // JSON
            $table->text('description')->nullable();
            $table->decimal('estimated_cost', 15, 2)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->unique(['category_id', 'brand_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_models');
        Schema::dropIfExists('equipment_brands');
        Schema::dropIfExists('equipment_categories');
    }
};
