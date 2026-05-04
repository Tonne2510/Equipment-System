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
        Schema::table('equipment_status_history', function (Blueprint $table) {
            $table->enum('old_status', ['available', 'borrowed', 'maintenance', 'damaged', 'lost'])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_status_history', function (Blueprint $table) {
            $table->enum('old_status', ['available', 'borrowed', 'maintenance', 'damaged', 'lost'])->nullable(false)->change();
        });
    }
};
