<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\BorrowRequestItem;
use App\Models\EquipmentItem;
use App\Models\BorrowHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowRequestController extends Controller
{
    public function create(Request $request)
    {
        $selectedEquipment = null;
        if ($request->has('equipment')) {
            $selectedEquipment = EquipmentItem::with(['model.category'])->find($request->equipment);
        }
        
        return view('employee.borrowings.create', compact('selectedEquipment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_ids' => 'required|array|min:1',
            'equipment_ids.*' => 'exists:equipment_items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'required|string|min:6|max:500'
        ]);

        // Check availability
        foreach ($validated['equipment_ids'] as $equipmentId) {
            $item = EquipmentItem::find($equipmentId);
            if ($item->status !== 'available') {
                return back()->withErrors("Thiết bị {$item->model->name} không đủ điều kiện mượn");
            }

            $conflict = BorrowRequest::whereHas('items', fn($q) => $q->where('equipment_item_id', $equipmentId))
                ->whereIn('status', ['approved', 'borrowed'])
                ->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                ->exists();

            if ($conflict) {
                return back()->withErrors("Thiết bị {$item->model->name} có xung đột thời gian mượn");
            }
        }

        $borrow = BorrowRequest::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason']
        ]);

        foreach ($validated['equipment_ids'] as $equipmentId) {
            BorrowRequestItem::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $equipmentId
            ]);

            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $equipmentId,
                'user_id' => auth()->id(),
                'action' => 'requested',
                'action_at' => now()
            ]);
        }

        return redirect()->route('employee.borrowings.show', $borrow)->with('success', 'Yêu cầu mượn đã được gửi');
    }

    public function requestRenewal(Request $request, BorrowRequest $borrow)
    {
        abort_if($borrow->user_id !== auth()->id(), 403);

        if ($borrow->status !== 'borrowed') {
            return back()->withErrors('Chỉ có thể gia hạn yêu cầu đang mượn');
        }

        // Auto-calculate new end date (7 days from current end date)
        $newEndDate = $borrow->end_date->addDays(7);

        // Check for conflicts with new end date
        foreach ($borrow->items as $item) {
            $conflict = BorrowRequest::where('id', '!=', $borrow->id)
                ->whereStatus('borrowed')
                ->whereHas('items', fn($q) => $q->where('equipment_item_id', $item->id))
                ->where('start_date', '<=', $newEndDate)
                ->where('end_date', '>=', $borrow->end_date)
                ->exists();

            if ($conflict) {
                return back()->withErrors("Không thể gia hạn vì có xung đột với yêu cầu khác");
            }
        }

        $renewalRequest = $borrow->renewalRequests()->create([
            'new_end_date' => $newEndDate,
            'status' => 'pending',
            'reason' => $request->input('reason') ?? null
        ]);

        // Update borrow status to indicate renewal request is pending
        $borrow->update(['status' => 'renewal_requested']);

        return back()->with('success', 'Yêu cầu gia hạn đã được gửi');
    }

    public function returnEquipment(Request $request, BorrowRequest $borrow)
    {
        abort_if($borrow->user_id !== auth()->id(), 403);

        // Check if only status is 'borrowed' (no renewal or return request already pending)
        if ($borrow->status !== 'borrowed') {
            return back()->withErrors('Chỉ có thể trả yêu cầu đang mượn. Vui lòng chờ xử lý yêu cầu hiện tại.');
        }

        // Check if there's already a pending renewal request
        $pendingRenewal = $borrow->renewalRequests()->where('status', 'pending')->first();
        if ($pendingRenewal) {
            return back()->withErrors('Bạn đã có yêu cầu gia hạn đang chờ xử lý. Không thể trả đồng thời.');
        }

        // Create return request
        $returnRequest = $borrow->returnRequests()->create([
            'status' => 'pending',
            'reason' => $request->input('reason') ?? 'Trả thiết bị theo thỏa thuận'
        ]);

        // Update borrow status to indicate return request is pending
        $borrow->update(['status' => 'return_requested']);

        return back()->with('success', 'Yêu cầu trả thiết bị đã được gửi đến admin');
    }

    public function cancelRequest(BorrowRequest $borrow)
    {
        abort_if($borrow->user_id !== auth()->id(), 403);

        if ($borrow->status !== 'pending') {
            return back()->withErrors('Chỉ có thể hủy yêu cầu chờ duyệt');
        }

        $borrow->update(['status' => 'cancelled']);

        return back()->with('success', 'Yêu cầu mượn đã được hủy');
    }
}
