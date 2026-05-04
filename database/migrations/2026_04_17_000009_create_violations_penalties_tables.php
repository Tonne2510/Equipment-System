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
        Schema::create('violation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('borrow_request_id')->nullable()->constrained('borrow_requests')->onDelete('set null');
            $table->enum('violation_type', ['overdue', 'equipment_damaged', 'equipment_lost'])->default('overdue');
            $table->text('description');
            $table->enum('status', ['active', 'resolved', 'waived'])->default('active');
            $table->date('violation_date');
            $table->date('resolved_date')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('violation_date');
        });

        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('violation_record_id')->nullable()->constrained('violation_records')->onDelete('set null');
            $table->enum('penalty_type', ['overdue_fee', 'damage_fee', 'loss_fee', 'other'])->default('overdue_fee');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalties');
        Schema::dropIfExists('violation_records');
    }
};
