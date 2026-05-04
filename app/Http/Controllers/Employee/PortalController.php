<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\EquipmentCategory;
use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $myLoans = $user->borrowRequests()
            ->with(['items.model'])
            ->latest()
            ->limit(5)
            ->get();

        $recentLoans = $user->borrowRequests()
            ->with(['items.model'])
            ->latest()
            ->limit(5)
            ->get();

        $topEquipment = EquipmentItem::with(['model.category'])
            ->where('status', 'available')
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $activeLoan = $user->borrowRequests()
            ->where('status', 'borrowed')
            ->first();

        $pendingRequests = $user->borrowRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        $violations = $user->violationRecords()
            ->where('status', 'active')
            ->count();

        $unpaidPenalties = $user->penalties()
            ->where('status', 'unpaid')
            ->sum('amount');

        return view('employee.dashboard', compact(
            'myLoans', 'recentLoans', 'topEquipment', 'activeLoan', 'pendingRequests', 'violations', 'unpaidPenalties'
        ));
    }

    public function browseEquipment(Request $request)
    {
        $query = EquipmentItem::with(['model.category', 'model.brand']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->whereHas('model.category', fn($q) => $q->where('id', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('serial_number', 'like', "%$search%")
                  ->orWhereHas('model', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        $equipment = $query->get();
        $categories = EquipmentCategory::where('status', 1)->get();

        return view('employee.equipment-browse', compact('equipment', 'categories'));
    }

    public function showEquipment(EquipmentItem $equipment)
    {
        $equipment->load(['model.category', 'model.brand', 'borrowRequests.user']);

        if ($equipment->status !== 'available') {
            return back()->withErrors('Thiết bị này không còn sẵn sàng để mượn');
        }

        return view('employee.equipment-detail', compact('equipment'));
    }

    public function myBorrowings(Request $request)
    {
        $query = auth()->user()->borrowRequests()->with(['items.model']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->get();

        return view('employee.borrowings.index', compact('borrowings'));
    }

    public function showBorrowing(BorrowRequest $borrow)
    {
        abort_if($borrow->user_id !== auth()->id(), 403);

        $borrow->load(['items.model.category', 'items.model.brand', 'approvedBy', 'history']);
        $canRenew = $borrow->status === 'borrowed' && $borrow->end_date->isFuture();

        return view('employee.borrowings.show', compact('borrow', 'canRenew'));
    }
}
