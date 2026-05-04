<?php
/**
 * Check for Orphaned Borrow Requests
 * 
 * This script identifies borrow requests that don't have valid user references.
 * Run this from the command line: php check_borrow_data.php
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BorrowRequest;
use App\Models\User;

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║       Kiểm Tra Dữ Liệu Yêu Cầu Mượn                        ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// 1. Count total borrow requests
$totalBorrows = BorrowRequest::count();
echo "📊 Tổng số yêu cầu mượn: " . $totalBorrows . "\n\n";

// 2. Find orphaned borrow requests (user_id is null)
echo "🔍 Tìm kiếm yêu cầu mượn không có người dùng...\n";
$orphanedByNull = BorrowRequest::whereNull('user_id')->get();
echo "   ├─ Yêu cầu mượn có user_id NULL: " . $orphanedByNull->count() . "\n";

if ($orphanedByNull->count() > 0) {
    foreach ($orphanedByNull as $borrow) {
        echo "   │  • ID #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . " - Trạng thái: " . $borrow->status . " - Ngày tạo: " . $borrow->created_at->format('d/m/Y H:i') . "\n";
    }
}

// 3. Find borrow requests where user was deleted
echo "\n🔍 Tìm kiếm yêu cầu mươn với user_id không tồn tại...\n";
$orphanedByDelete = BorrowRequest::whereNotNull('user_id')
    ->whereNotIn('user_id', User::pluck('id')->toArray())
    ->get();
echo "   ├─ Yêu cầu mượn có user_id không hợp lệ: " . $orphanedByDelete->count() . "\n";

if ($orphanedByDelete->count() > 0) {
    foreach ($orphanedByDelete as $borrow) {
        echo "   │  • ID #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . " - user_id: " . $borrow->user_id . " - Trạng thái: " . $borrow->status . "\n";
    }
}

// 4. Find borrow requests with invalid approved_by
echo "\n🔍 Tìm kiếm yêu cầu mượn với approved_by không hợp lệ...\n";
$invalidApprovedBy = BorrowRequest::whereNotNull('approved_by')
    ->whereNotIn('approved_by', User::pluck('id')->toArray())
    ->get();
echo "   ├─ Yêu cầu mượn có approved_by không hợp lệ: " . $invalidApprovedBy->count() . "\n";

if ($invalidApprovedBy->count() > 0) {
    foreach ($invalidApprovedBy as $borrow) {
        echo "   │  • ID #" . str_pad($borrow->id, 5, '0', STR_PAD_LEFT) . " - approved_by: " . $borrow->approved_by . "\n";
    }
}

// 5. Summary
$totalIssues = $orphanedByNull->count() + $orphanedByDelete->count() + $invalidApprovedBy->count();
echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║ TỔNG KÊTS:                                                 ║\n";
echo "║ ├─ Tổng yêu cầu mượn: " . str_pad($totalBorrows, 2, ' ', STR_PAD_LEFT) . "                                    ║\n";
echo "║ ├─ Yêu cầu mượn có vấn đề: " . str_pad($totalIssues, 2, ' ', STR_PAD_LEFT) . "                              ║\n";
echo "║ └─ Tình trạng: " . ($totalIssues === 0 ? "✓ Dữ liệu hoàn toàn" : "✗ Có dữ liệu lỗi") . "                   ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

if ($totalIssues > 0) {
    echo "💡 Gợi ý:\n";
    echo "   • Chạy: php artisan tinker\n";
    echo "   • Hoặc tạo migration để làm sạch dữ liệu\n";
    echo "   • Hoặc khôi phục dữ liệu từ backup\n\n";
}
