<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\BorrowRequest;

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "=== Database Check ===\n\n";

$borrow = BorrowRequest::find(24);

if ($borrow) {
    echo "✅ Found Borrow Request #24:\n";
    echo "  ID: " . $borrow->id . "\n";
    echo "  user_id: " . ($borrow->user_id ?? 'NULL') . "\n";
    echo "  status: " . $borrow->status . "\n";
    echo "  start_date: " . ($borrow->start_date ? $borrow->start_date->format('Y-m-d') : 'NULL') . "\n";
    echo "  end_date: " . ($borrow->end_date ? $borrow->end_date->format('Y-m-d') : 'NULL') . "\n";
    echo "  created_at: " . $borrow->created_at . "\n";
    echo "  updated_at: " . $borrow->updated_at . "\n";
    echo "  reason: " . ($borrow->reason ?? 'NULL') . "\n";
    echo "\n";
    
    $items = $borrow->items()->with('model')->get();
    echo "  Items: " . $items->count() . "\n";
    foreach ($items as $item) {
        echo "    - ID: " . $item->id . ", Model: " . ($item->model ? $item->model->name : 'NULL') . "\n";
    }
} else {
    echo "❌ Borrow Request #24 not found\n";
}

echo "\n=== Latest 5 Borrow Requests ===\n";
$latest = BorrowRequest::latest()->limit(5)->get();
foreach ($latest as $b) {
    echo "  #" . $b->id . " | user_id: " . ($b->user_id ?? 'NULL') . " | status: " . $b->status . " | created: " . $b->created_at->format('Y-m-d H:i') . "\n";
}
?>
