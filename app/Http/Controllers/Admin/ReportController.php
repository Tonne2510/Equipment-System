<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use App\Models\EquipmentItem;
use App\Models\ViolationRecord;
use App\Models\Penalty;
use App\Models\MaintenanceRecord;
use DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function equipmentUtilization()
    {
        $fromDate = request('from_date', now()->subMonths(3)->format('Y-m-d'));
        $toDate = request('to_date', now()->format('Y-m-d'));

        // Get top equipment without using with() to avoid ambiguous column errors
        $topEquipment = EquipmentItem::get()
            ->map(function($item) use ($fromDate, $toDate) {
                $borrows = BorrowRequest::whereIn('id', 
                    DB::table('borrow_request_items')
                        ->where('equipment_item_id', $item->id)
                        ->pluck('borrow_request_id')
                )
                    ->whereBetween('borrow_requests.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                    ->get();
                return (object)[
                    'id' => $item->id,
                    'serial_number' => $item->serial_number,
                    'model' => $item->model,
                    'status' => $item->status,
                    'borrow_count' => $borrows->count(),
                    'total_days' => $borrows->sum(function($b) { 
                        return $b->start_date && $b->end_date ? $b->end_date->diffInDays($b->start_date) : 0;
                    })
                ];
            })
            ->sortByDesc('borrow_count')
            ->take(10)
            ->values();

        // Status count - current status distribution of all equipment
        $statusCount = EquipmentItem::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $categoryStats = EquipmentItem::with('model.category')
            ->get()
            ->groupBy('model.category.id')
            ->map(function($items, $categoryId) use ($fromDate, $toDate) {
                $category = $items->first()->model->category;
                $borrowCount = 0;
                foreach($items as $item) {
                    $borrowCount += BorrowRequest::whereIn('id', 
                        DB::table('borrow_request_items')
                            ->where('equipment_item_id', $item->id)
                            ->pluck('borrow_request_id')
                    )
                        ->whereBetween('borrow_requests.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                        ->count();
                }
                return (object)[
                    'name' => $category->name ?? 'Unknown',
                    'items_count' => $items->count(),
                    'borrow_count' => $borrowCount
                ];
            })
            ->values();

        return view('admin.reports.utilization', compact('topEquipment', 'statusCount', 'categoryStats', 'fromDate', 'toDate'));
    }

    public function borrowingAnalysis()
    {
        $totalBorrows = BorrowRequest::count();
        $averageBorrowDuration = BorrowRequest::selectRaw('AVG(DATEDIFF(COALESCE(actual_return_date, end_date), start_date)) as avg_days')
            ->whereNotNull('start_date')
            ->value('avg_days');

        $borrowsByMonth = BorrowRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        $overdueBorrows = BorrowRequest::where('status', 'borrowed')
            ->where('end_date', '<', now())
            ->with('user', 'items')
            ->get();

        return view('admin.reports.borrowing-analysis', compact(
            'totalBorrows', 'averageBorrowDuration', 'borrowsByMonth', 'overdueBorrows'
        ));
    }

    public function violationReport()
    {
        $violations = ViolationRecord::with(['user', 'borrowRequest'])
            ->orderByDesc('created_at')
            ->paginate(50);

        $violationStats = ViolationRecord::selectRaw('violation_type, COUNT(*) as count')
            ->groupBy('violation_type')
            ->get();

        $topViolators = ViolationRecord::selectRaw('user_id, COUNT(*) as violation_count')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('violation_count')
            ->limit(10)
            ->get();

        return view('admin.reports.violations', compact('violations', 'violationStats', 'topViolators'));
    }

    public function penaltyReport()
    {
        $totalPenalties = Penalty::sum('amount');
        $unpaidPenalties = Penalty::where('status', 'unpaid')->sum('amount');
        $paidPenalties = Penalty::where('status', 'paid')->sum('amount');

        $penaltiesByType = Penalty::selectRaw('penalty_type, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('penalty_type')
            ->get();

        $topPenalizedUsers = Penalty::selectRaw('user_id, COUNT(*) as count, SUM(amount) as total')
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.reports.penalties', compact(
            'totalPenalties', 'unpaidPenalties', 'paidPenalties', 'penaltiesByType', 'topPenalizedUsers'
        ));
    }

    public function employeeBorrowingReport()
    {
        $fromDate = request('from_date', now()->subMonths(1)->format('Y-m-d'));
        $toDate = request('to_date', now()->format('Y-m-d'));
        $employeeId = request('employee_id');

        $query = BorrowRequest::whereBetween('borrow_requests.created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        
        if ($employeeId) {
            $query->where('user_id', $employeeId);
        }

        if ($employeeId) {
            $query->where('user_id', $employeeId);
        }

        // Get paginated results
        $borrowings = $query
            ->orderByDesc('borrow_requests.created_at')
            ->with('user', 'items.model')
            ->paginate(30);

        // Get employee list for filter
        $employees = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'employee'))
            ->orderBy('name')
            ->get();

        // Get statistics
        $stats = [
            'total_borrows' => BorrowRequest::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])->count(),
            'total_items_borrowed' => BorrowRequestItem::whereIn('borrow_request_id', 
                BorrowRequest::whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])->pluck('id')
            )->count(),
            'overdue_count' => BorrowRequest::where('status', 'borrowed')
                ->where('end_date', '<', now())
                ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                ->count(),
        ];

        return view('admin.reports.employee-borrowing', compact(
            'borrowings', 'employees', 'stats', 'fromDate', 'toDate', 'employeeId'
        ));
    }
}
