<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('app:reset-auto-increment {table}')]
#[Description('Reset AUTO_INCREMENT for a table')]
class ResetAutoIncrement extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $table = $this->argument('table');
        $maxId = DB::table($table)->max('id') ?? 0;
        DB::statement("ALTER TABLE $table AUTO_INCREMENT = " . ($maxId + 1));
        $this->info("AUTO_INCREMENT for table '$table' has been reset to " . ($maxId + 1));
    }
}
