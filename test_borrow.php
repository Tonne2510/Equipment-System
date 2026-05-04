<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$borrow = \App\Models\BorrowRequest::with('user', 'user.role')->find(14);

if ($borrow) {
    echo "✓ Found borrow #14\n";
    echo "  User ID: " . $borrow->user_id . "\n";
    echo "  User Name: " . ($borrow->user?->name ?? 'null') . "\n";
    echo "  User Email: " . ($borrow->user?->email ?? 'null') . "\n";
    echo "  Status: " . $borrow->status . "\n";
    echo "  Start Date: " . ($borrow->start_date?->format('d/m/Y') ?? 'null') . "\n";
    echo "  End Date: " . ($borrow->end_date?->format('d/m/Y') ?? 'null') . "\n";
    echo "  Items Count: " . $borrow->items->count() . "\n";
} else {
    echo "✗ Borrow #14 not found\n";
}
