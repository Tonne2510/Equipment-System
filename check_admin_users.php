<?php
// Check admin users in database

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\User;

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = Illuminate\Http\Request::capture());

echo "=== Checking Admin Users ===\n\n";

$admins = User::whereHas('role', fn($q) => $q->where('name', 'admin'))->get();
$managers = User::whereHas('role', fn($q) => $q->where('name', 'manager'))->get();

echo "Admin Users: " . $admins->count() . "\n";
foreach ($admins as $admin) {
    echo "  - " . $admin->name . " (" . $admin->email . ")\n";
}

echo "\nManager Users: " . $managers->count() . "\n";
foreach ($managers as $manager) {
    echo "  - " . $manager->name . " (" . $manager->email . ")\n";
}

echo "\nAll Users: " . User::count() . "\n";
echo "First User: " . (User::first() ? User::first()->email : 'None') . "\n";
?>
