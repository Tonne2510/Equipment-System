<?php
/**
 * Script to cleanup orphaned borrow requests (missing user references)
 * 
 * Usage: php artisan tinker < cleanup_orphaned_borrows.php
 * Or run individual commands in tinker
 */

// Check for orphaned borrow requests
echo "=== Checking for orphaned borrow requests ===\n";
$orphaned = \App\Models\BorrowRequest::whereNull('user_id')->get();
echo "Found " . $orphaned->count() . " orphaned borrow requests\n";

if ($orphaned->count() > 0) {
    echo "\nOrphaned borrow request IDs:\n";
    foreach ($orphaned as $borrow) {
        echo "- #" . $borrow->id . " (Status: {$borrow->status}, Created: {$borrow->created_at})\n";
    }
    
    echo "\n=== Deleting orphaned borrow requests ===\n";
    $deleted = \App\Models\BorrowRequest::whereNull('user_id')->delete();
    echo "Deleted $deleted orphaned borrow requests\n";
}

// Check for renewal requests with missing borrow relationships
echo "\n=== Checking for renewal requests with missing borrow relationships ===\n";
$renewalOrphaned = \App\Models\RenewalRequest::whereDoesntHave('borrowRequest')
                                              ->orWhereHas('borrowRequest', fn($q) => $q->whereNull('user_id'))
                                              ->get();
echo "Found " . $renewalOrphaned->count() . " renewal requests with issues\n";

if ($renewalOrphaned->count() > 0) {
    echo "\n=== Deleting renewal requests with issues ===\n";
    $deletedRenewals = \App\Models\RenewalRequest::whereDoesntHave('borrowRequest')
                                                   ->orWhereHas('borrowRequest', fn($q) => $q->whereNull('user_id'))
                                                   ->delete();
    echo "Deleted $deletedRenewals renewal requests\n";
}

// Check for return requests with missing borrow relationships
echo "\n=== Checking for return requests with missing borrow relationships ===\n";
$returnOrphaned = \App\Models\ReturnRequest::whereDoesntHave('borrowRequest')
                                            ->orWhereHas('borrowRequest', fn($q) => $q->whereNull('user_id'))
                                            ->get();
echo "Found " . $returnOrphaned->count() . " return requests with issues\n";

if ($returnOrphaned->count() > 0) {
    echo "\n=== Deleting return requests with issues ===\n";
    $deletedReturns = \App\Models\ReturnRequest::whereDoesntHave('borrowRequest')
                                                ->orWhereHas('borrowRequest', fn($q) => $q->whereNull('user_id'))
                                                ->delete();
    echo "Deleted $deletedReturns return requests\n";
}

echo "\n=== Cleanup completed ===\n";
