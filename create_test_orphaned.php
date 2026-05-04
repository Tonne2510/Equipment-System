<?php
// Script to create a test case with orphaned borrow request

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use App\Models\EquipmentItem;
use Illuminate\Support\Facades\DB;

// Initialize Laravel app
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "=== Creating test case: Orphaned Borrow Request ===\n\n";

// Get any equipment item
$equipment = EquipmentItem::first();

if (!$equipment) {
    echo "❌ No equipment found in database. Cannot create test case.\n";
    exit(1);
}

// Create a borrow request with NULL user_id
$borrow = BorrowRequest::create([
    'user_id' => null,  // This is the key - NULL user
    'status' => 'pending',
    'start_date' => now()->addDay(),
    'end_date' => now()->addDays(7),
    'reason' => 'Test case for orphaned borrow request (user deleted from system)'
]);

// Add equipment to the request
BorrowRequestItem::create([
    'borrow_request_id' => $borrow->id,
    'equipment_item_id' => $equipment->id
]);

echo "✅ Test case created successfully!\n\n";
echo "Borrow Request Details:\n";
echo "  ID: #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . "\n";
echo "  user_id: NULL (Orphaned - User has been deleted)\n";
echo "  Status: pending\n";
echo "  Equipment: " . $equipment->model->name . " (Serial: " . $equipment->serial_number . ")\n";
echo "  Start Date: " . $borrow->start_date->format('d/m/Y') . "\n";
echo "  End Date: " . $borrow->end_date->format('d/m/Y') . "\n\n";
echo "🔗 Test URL: http://192.168.1.17:8000/admin/borrowing/" . $borrow->id . "\n";
echo "\n✨ You can now test the admin detail page with this orphaned request.\n";
echo "   The page should now:\n";
echo "   1. Show a warning about the missing user\n";
echo "   2. Allow admin to still approve/reject the request\n";
echo "   3. Display all equipment and details normally\n";
?>
