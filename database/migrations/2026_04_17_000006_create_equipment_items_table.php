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
        Schema::create('equipment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_id')->constrained('equipment_models')->onDelete('cascade');
            $table->string('serial_number')->unique(); // Định danh duy nhất cho từng thiết bị
            $table->string('asset_tag')->nullable()->unique();
            $table->enum('status', ['available', 'borrowed', 'maintenance', 'damaged', 'lost'])->default('available');
            $table->string('location')->nullable(); // Vị trí tủ/kho
            $table->text('notes')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 15, 2)->nullable();
            $table->string('image')->nullable();
            $table->date('warranty_until')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('serial_number');
            $table->index('asset_tag');
        });

        Schema::create('equipment_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained('equipment_items')->onDelete('cascade');
            $table->enum('old_status', ['available', 'borrowed', 'maintenance', 'damaged', 'lost']);
            $table->enum('new_status', ['available', 'borrowed', 'maintenance', 'damaged', 'lost']);
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['equipment_item_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_status_history');
        Schema::dropIfExists('equipment_items');
    }
};
