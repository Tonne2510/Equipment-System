<?php
require 'vendor/autoload.php';
require 'bootstrap/app.php';

use App\Models\BorrowRequest;

$borrow = BorrowRequest::with(['user', 'items', 'approvedBy'])->find(15);

if ($borrow) {
    echo "Borrow ID 15 found:\n";
    echo "User ID: " . ($borrow->user_id ?? 'NULL') . "\n";
    echo "User Name: " . ($borrow->user?->name ?? 'NULL') . "\n";
    echo "Status: " . ($borrow->status ?? 'NULL') . "\n";
    echo "Start Date: " . ($borrow->start_date ?? 'NULL') . "\n";
    echo "End Date: " . ($borrow->end_date ?? 'NULL') . "\n";
    echo "Reason: " . ($borrow->reason ?? 'NULL') . "\n";
    echo "Items Count: " . $borrow->items->count() . "\n";
} else {
    echo "No borrow request found with ID 15\n";
}
?>
