<?php
/**
 * Debug Borrow Request #19
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BorrowRequest;
use Illuminate\Support\Facades\DB;

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║           DEBUG - BORROW REQUEST #19                       ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Get raw data
echo "📊 RAW DATABASE DATA:\n";
$raw = DB::table('borrow_requests')->where('id', 19)->first();
if ($raw) {
    echo "   ID: " . $raw->id . "\n";
    echo "   user_id: " . ($raw->user_id ?? 'NULL') . "\n";
    echo "   status: " . $raw->status . "\n";
    echo "   approved_by: " . ($raw->approved_by ?? 'NULL') . "\n";
    echo "   start_date: " . $raw->start_date . "\n";
    echo "   end_date: " . $raw->end_date . "\n";
    echo "   created_at: " . $raw->created_at . "\n";
} else {
    echo "   ❌ NOT FOUND\n";
    exit;
}

// 2. Get with relationships
echo "\n📦 WITH RELATIONSHIPS:\n";
$borrow = BorrowRequest::with(['user', 'items', 'approvedBy'])->find(19);
if ($borrow) {
    echo "   Borrow ID: " . $borrow->id . "\n";
    echo "   User: " . ($borrow->user ? $borrow->user->name . " (#" . $borrow->user->id . ")" : "NULL") . "\n";
    echo "   Items count: " . $borrow->items->count() . "\n";
    echo "   ApprovedBy: " . ($borrow->approvedBy ? $borrow->approvedBy->name : "NULL") . "\n";
} else {
    echo "   ❌ CANNOT LOAD\n";
}

// 3. Check database enum values
echo "\n🔧 DATABASE ENUM VALUES FOR 'status':\n";
$columns = DB::select("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='borrow_requests' AND COLUMN_NAME='status'");
if ($columns) {
    echo "   " . $columns[0]->COLUMN_TYPE . "\n";
} else {
    echo "   ❌ CANNOT FIND\n";
}

// 4. Check for items
echo "\n📦 BORROW ITEMS FOR #19:\n";
$items = DB::table('borrow_request_items')->where('borrow_request_id', 19)->get();
echo "   Total items: " . count($items) . "\n";
if ($items->count() > 0) {
    foreach ($items as $item) {
        echo "   - Equipment ID: " . $item->equipment_item_id . "\n";
    }
} else {
    echo "   No items found\n";
}

echo "\n╔════════════════════════════════════════════════════════════╗\n";
echo "║                    DEBUG COMPLETE                          ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";
