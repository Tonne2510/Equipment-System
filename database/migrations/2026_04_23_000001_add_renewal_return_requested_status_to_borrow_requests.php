<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to modify the enum type
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `borrow_requests` MODIFY `status` ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned', 'cancelled', 'renewal_requested', 'return_requested') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `borrow_requests` MODIFY `status` ENUM('pending', 'approved', 'rejected', 'borrowed', 'returned', 'cancelled') DEFAULT 'pending'");
        }
    }
};
