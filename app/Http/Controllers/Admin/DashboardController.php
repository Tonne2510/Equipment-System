<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use App\Models\User;
use App\Models\IncidentReport;
use App\Models\MaintenanceRecord;
use App\Models\ViolationRecord;
use App\Models\Penalty;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Equipment Statistics
        $totalEquipment = EquipmentItem::count();
        $availableEquipment = EquipmentItem::where('status', 'available')->count();
        $borrowedEquipment = EquipmentItem::where('status', 'borrowed')->count();
        $maintenanceEquipment = EquipmentItem::where('status', 'maintenance')->count();
        $damagedEquipment = EquipmentItem::where('status', 'damaged')->count();

        // Borrowing Statistics
        $totalBorrows = BorrowRequest::count();
        $pendingRequests = BorrowRequest::where('status', 'pending')->count();
        $activeBorrows = BorrowRequest::where('status', 'borrowed')->count();
        $overdueCount = BorrowRequest::where('status', 'borrowed')
            ->where('end_date', '<', now())
            ->count();

        // User Statistics
        $totalUsers = User::count();
        $totalEmployees = User::whereHas('role', fn($q) => $q->where('name', 'employee'))->count();
        $totalManagers = User::whereHas('role', fn($q) => $q->where('name', 'manager'))->count();

        // Incidents & Maintenance
        $openIncidents = IncidentReport::where('status', '!=', 'closed')->count();
        $criticalIncidents = IncidentReport::where('severity', 'critical')->where('status', '!=', 'closed')->count();
        $maintenanceInProgress = MaintenanceRecord::where('status', 'in-progress')->count();

        // Violations & Penalties
        $activeViolations = ViolationRecord::where('status', 'active')->count();
        $unpaidPenalties = Penalty::where('status', 'unpaid')->sum('amount');

        // Recent Activities
        $recentBorrows = BorrowRequest::with(['user', 'items'])->latest()->limit(10)->get();
        $recentIncidents = IncidentReport::with(['equipment', 'reportedBy'])->latest()->limit(5)->get();
        $pendingApprovals = BorrowRequest::where('status', 'pending')->with('user')->latest()->limit(5)->get();

        // Top Equipment - Fixed query for many-to-many relationship
        $topEquipment = BorrowRequestItem::selectRaw('equipment_item_id, COUNT(*) as borrow_count')
            ->groupBy('equipment_item_id')
            ->orderByDesc('borrow_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEquipment', 'availableEquipment', 'borrowedEquipment', 'maintenanceEquipment', 'damagedEquipment',
            'totalBorrows', 'pendingRequests', 'activeBorrows', 'overdueCount',
            'totalUsers', 'totalEmployees', 'totalManagers',
            'openIncidents', 'criticalIncidents', 'maintenanceInProgress',
            'activeViolations', 'unpaidPenalties',
            'recentBorrows', 'recentIncidents', 'pendingApprovals', 'topEquipment'
        ));
    }
}
