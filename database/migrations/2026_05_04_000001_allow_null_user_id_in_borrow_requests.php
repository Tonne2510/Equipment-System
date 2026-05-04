<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change user_id to nullable and set foreign key to SET NULL
     * This allows handling orphaned borrow requests gracefully
     */
    public function up(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Drop existing foreign key
            if (DB::connection()->getDriverName() === 'mysql') {
                // MySQL: drop by name
                $table->dropForeign(['user_id']);
            }
        });

        Schema::table('borrow_requests', function (Blueprint $table) {
            // Make user_id nullable and set foreign key to SET NULL
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });

        // Also update borrow_history to handle null user_id
        Schema::table('borrow_history', function (Blueprint $table) {
            // The user_id in borrow_history should also be nullable
            // (as a record of who made the action, might be admin or system)
            // This is already set to cascade, so no change needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrow_requests', function (Blueprint $table) {
            // Revert to cascade
            if (DB::connection()->getDriverName() === 'mysql') {
                $table->dropForeign(['user_id']);
            }
        });

        Schema::table('borrow_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};
