<?php
// Test script to check for orphaned borrow requests and create one for testing

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\BorrowRequest;
use App\Models\User;
use App\Models\EquipmentItem;
use Illuminate\Support\Facades\DB;

// Initialize Laravel app
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "=== Checking for orphaned borrow requests ===\n\n";

// Find existing orphaned requests
$orphaned = BorrowRequest::whereNull('user_id')->get();
echo "Found " . $orphaned->count() . " orphaned borrow requests:\n";

foreach ($orphaned as $borrow) {
    echo "  ✓ ID #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . " | Status: " . ucfirst($borrow->status) . " | Created: " . $borrow->created_at->format('d/m/Y H:i') . "\n";
    echo "    → Test URL: http://192.168.1.17:8000/admin/borrowing/" . $borrow->id . "\n";
}

echo "\n=== Checking for deleted users with active borrow requests ===\n\n";

// Find requests where user was deleted
$invalidUserIds = BorrowRequest::whereNotNull('user_id')
    ->whereNotIn('user_id', User::pluck('id')->toArray())
    ->pluck('user_id')
    ->unique()
    ->toArray();

if (count($invalidUserIds) > 0) {
    echo "Found " . count($invalidUserIds) . " deleted user IDs in borrow requests:\n";
    echo "  User IDs: " . implode(', ', $invalidUserIds) . "\n\n";
    
    $invalidBorrows = BorrowRequest::whereIn('user_id', $invalidUserIds)->get();
    echo "Found " . $invalidBorrows->count() . " borrow requests with deleted users:\n";
    
    foreach ($invalidBorrows as $borrow) {
        echo "  ✓ ID #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . " | user_id: " . $borrow->user_id . " | Status: " . ucfirst($borrow->status) . "\n";
        echo "    → Test URL: http://192.168.1.17:8000/admin/borrowing/" . $borrow->id . "\n";
    }
} else {
    echo "No borrow requests with deleted users found.\n";
}

echo "\n=== Summary ===\n";
echo "Total orphaned/invalid borrow requests: " . ($orphaned->count() + (isset($invalidBorrows) ? $invalidBorrows->count() : 0)) . "\n";
echo "\n";
?>
