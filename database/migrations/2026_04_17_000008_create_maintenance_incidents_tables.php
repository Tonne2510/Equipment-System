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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained('equipment_items')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->enum('incident_type', ['damaged', 'malfunction', 'lost', 'theft', 'other'])->default('malfunction');
            $table->text('description');
            $table->text('image_path')->nullable(); // JSON array of image paths
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'assigned', 'in-progress', 'resolved', 'closed'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution_notes')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('severity');
            $table->index(['reported_by', 'created_at']);
        });

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_item_id')->constrained('equipment_items')->onDelete('cascade');
            $table->foreignId('incident_report_id')->nullable()->constrained('incident_reports')->onDelete('set null');
            $table->enum('maintenance_type', ['preventive', 'corrective', 'upgrade'])->default('corrective');
            $table->text('description');
            $table->dateTime('scheduled_start')->nullable();
            $table->dateTime('scheduled_end')->nullable();
            $table->dateTime('actual_start')->nullable();
            $table->dateTime('actual_end')->nullable();
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('work_done')->nullable();
            $table->decimal('cost', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['scheduled', 'in-progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index('status');
            $table->index(['equipment_item_id', 'actual_end']);
        });

        Schema::create('maintenance_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_record_id')->constrained('maintenance_records')->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 15, 2);
            $table->enum('category', ['labor', 'parts', 'service', 'other'])->default('other');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_costs');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('incident_reports');
    }
};
