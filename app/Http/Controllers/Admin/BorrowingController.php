<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowRequest;
use App\Models\RenewalRequest;
use App\Models\ReturnRequest;
use App\Models\User;
use App\Models\EquipmentItem;
use App\Models\BorrowHistory;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = BorrowRequest::with(['user', 'items.model.category', 'items.model.brand']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%")
                                                   ->orWhere('email', 'like', "%$search%"));
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $borrows = $query->latest()->paginate(20);

        return view('admin.borrowing.index', compact('borrows'));
    }

    public function show(BorrowRequest $borrow)
    {
        $borrow->load([
            'user.role',
            'items.model.category',
            'items.model.brand',
            'approvedBy',
            'history',
            'renewalRequests',
            'returnRequests'
        ]);
        
        // Nếu không có user, vẫn hiển thị trang nhưng với dữ liệu khả dụng
        // không redirect - để admin có thể thấy thông tin yêu cầu mượn
        
        return view('admin.borrowing.show', compact('borrow'));
    }

    public function approve(Request $request, BorrowRequest $borrow)
    {
        if ($borrow->status !== 'pending') {
            return back()->withErrors('Chỉ có thể duyệt yêu cầu chờ duyệt');
        }

        // Check for conflicts
        foreach ($borrow->items as $item) {
            $conflict = BorrowRequest::where('status', 'approved')
                ->orWhere('status', 'borrowed')
                ->whereHas('items', fn($q) => $q->where('equipment_item_id', $item->id))
                ->whereBetween('start_date', [$borrow->start_date, $borrow->end_date])
                ->exists();

            if ($conflict) {
                return back()->withErrors("Thiết bị {$item->model->name} có xung đột thời gian mượn");
            }
        }

        $borrow->update([
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);

        // Create history entry for each equipment item
        foreach ($borrow->items as $item) {
            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $item->id,
                'user_id' => $borrow->user_id,
                'action' => 'approved',
                'action_at' => now(),
                'action_by' => auth()->id()
            ]);
        }

        return back()->with('success', 'Duyệt yêu cầu mượn thành công');
    }

    public function reject(Request $request, BorrowRequest $borrow)
    {
        if (!in_array($borrow->status, ['pending', 'approved'])) {
            return back()->withErrors('Không thể từ chối yêu cầu này');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10'
        ]);

        $borrow->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Create history entry for each equipment item
        foreach ($borrow->items as $item) {
            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $item->id,
                'user_id' => $borrow->user_id,
                'action' => 'rejected',
                'action_at' => now(),
                'action_by' => auth()->id(),
                'notes' => $validated['rejection_reason']
            ]);
        }

        return back()->with('success', 'Từ chối yêu cầu mượn');
    }

    public function markBorrowed(Request $request, BorrowRequest $borrow)
    {
        if ($borrow->status !== 'approved') {
            return back()->withErrors('Chỉ có thể ghi nhận giao cho yêu cầu đã duyệt');
        }

        $borrow->update([
            'status' => 'borrowed'
        ]);

        foreach ($borrow->items as $item) {
            $item->update(['status' => 'borrowed']);
            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $item->id,
                'user_id' => $borrow->user_id,
                'action' => 'borrowed',
                'action_at' => now(),
                'action_by' => auth()->id()
            ]);
        }

        return back()->with('success', 'Ghi nhận giao thiết bị thành công');
    }

    public function markReturned(Request $request, BorrowRequest $borrow)
    {
        if ($borrow->status !== 'borrowed') {
            return back()->withErrors('Chỉ có thể ghi nhận trả yêu cầu đang mượn');
        }

        $validated = $request->validate([
            'condition' => 'required|in:good,damaged,lost',
            'damage_notes' => 'nullable|string'
        ]);

        $borrow->update([
            'status' => 'returned',
            'actual_return_date' => now()
        ]);

        foreach ($borrow->items as $item) {
            $newStatus = 'available';
            if ($validated['condition'] === 'damaged') {
                $newStatus = 'damaged';
            } elseif ($validated['condition'] === 'lost') {
                $newStatus = 'lost';
            }

            $item->update(['status' => $newStatus]);

            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $item->id,
                'user_id' => $borrow->user_id,
                'action' => 'returned',
                'action_at' => now(),
                'action_by' => auth()->id(),
                'notes' => $validated['damage_notes'] ?? null
            ]);
        }

        return back()->with('success', 'Ghi nhận trả thiết bị thành công');
    }

    // ===== RENEWAL REQUESTS =====
    public function renewalRequests(Request $request)
    {
        $query = RenewalRequest::with(['borrowRequest.user', 'borrowRequest.items.model', 'approvedBy'])
                    ->whereHas('borrowRequest.user');  // Chỉ lấy renewal có user hợp lệ

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $renewals = $query->latest()->paginate(20);

        return view('admin.borrowing.renewals', compact('renewals'));
    }

    public function approveRenewal(Request $request, RenewalRequest $renewal)
    {
        if ($renewal->status !== 'pending') {
            return back()->withErrors('Yêu cầu gia hạn này không thể xử lý');
        }

        $borrow = $renewal->borrowRequest;

        // Check for conflicts with new end date
        foreach ($borrow->items as $item) {
            $conflict = BorrowRequest::where('id', '!=', $borrow->id)
                ->whereIn('status', ['borrowed', 'approved'])
                ->whereHas('items', fn($q) => $q->where('equipment_item_id', $item->id))
                ->where('start_date', '<=', $renewal->new_end_date)
                ->where('end_date', '>=', $borrow->end_date)
                ->exists();

            if ($conflict) {
                return back()->withErrors("Không thể gia hạn vì có xung đột với yêu cầu khác");
            }
        }

        $renewal->update([
            'status' => 'approved',
            'approved_by' => auth()->id()
        ]);

        // Update borrow: set new end date and change status back to 'borrowed'
        $borrow->update([
            'end_date' => $renewal->new_end_date,
            'status' => 'borrowed'
        ]);

        return back()->with('success', 'Duyệt gia hạn thiết bị thành công');
    }

    public function rejectRenewal(Request $request, RenewalRequest $renewal)
    {
        if ($renewal->status !== 'pending') {
            return back()->withErrors('Yêu cầu gia hạn này không thể từ chối');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:5'
        ]);

        $renewal->update([
            'status' => 'rejected',
            'reason' => $validated['reason'],
            'approved_by' => auth()->id()
        ]);

        // Update borrow status back to 'borrowed' since renewal was rejected
        $renewal->borrowRequest->update(['status' => 'borrowed']);

        return back()->with('success', 'Từ chối yêu cầu gia hạn');
    }

    // ===== RETURN REQUESTS =====
    public function returnRequests(Request $request)
    {
        $query = ReturnRequest::with(['borrowRequest.user', 'borrowRequest.items.model', 'approvedBy'])
                    ->whereHas('borrowRequest.user');  // Chỉ lấy return có user hợp lệ

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $returns = $query->latest()->paginate(20);

        return view('admin.borrowing.returns', compact('returns'));
    }

    public function approveReturn(Request $request, ReturnRequest $returnReq)
    {
        if ($returnReq->status !== 'pending') {
            return back()->withErrors('Yêu cầu trả thiết bị này không thể xử lý');
        }

        $validated = $request->validate([
            'condition' => 'required|in:good,damaged,lost',
            'notes' => 'nullable|string'
        ]);

        $borrow = $returnReq->borrowRequest;

        $returnReq->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'notes' => $validated['notes'] ?? null
        ]);

        $borrow->update([
            'status' => 'returned',
            'actual_return_date' => now()
        ]);

        // Update equipment status based on condition
        foreach ($borrow->items as $item) {
            $newStatus = 'available';
            if ($validated['condition'] === 'damaged') {
                $newStatus = 'damaged';
            } elseif ($validated['condition'] === 'lost') {
                $newStatus = 'lost';
            }

            $item->update(['status' => $newStatus]);

            BorrowHistory::create([
                'borrow_request_id' => $borrow->id,
                'equipment_item_id' => $item->id,
                'user_id' => $borrow->user_id,
                'action' => 'returned',
                'action_at' => now(),
                'action_by' => auth()->id(),
                'notes' => $validated['notes'] ?? null
            ]);
        }

        return back()->with('success', 'Duyệt trả thiết bị thành công');
    }

    public function rejectReturn(Request $request, ReturnRequest $returnReq)
    {
        if ($returnReq->status !== 'pending') {
            return back()->withErrors('Yêu cầu trả thiết bị này không thể từ chối');
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:5'
        ]);

        $returnReq->update([
            'status' => 'rejected',
            'reason' => $validated['reason'],
            'approved_by' => auth()->id()
        ]);

        // Update borrow status back to 'borrowed' since return was rejected
        $returnReq->borrowRequest->update(['status' => 'borrowed']);

        return back()->with('success', 'Từ chối yêu cầu trả thiết bị');
    }

    public function showReturn(ReturnRequest $returnReq)
    {
        $returnReq->load(['borrowRequest.user', 'borrowRequest.items.model.category', 'borrowRequest.items.model.brand']);
        
        if (!$returnReq->borrowRequest || !$returnReq->borrowRequest->user) {
            return back()->withErrors('Yêu cầu trả này không có dữ liệu hợp lệ.');
        }
        
        return view('admin.borrowing.return_detail', compact('returnReq'));
    }

    public function showRenewal(RenewalRequest $renewal)
    {
        $renewal->load(['borrowRequest.user', 'borrowRequest.items.model.category']);
        
        if (!$renewal->borrowRequest || !$renewal->borrowRequest->user) {
            return back()->withErrors('Yêu cầu gia hạn này không có dữ liệu hợp lệ.');
        }
        
        return view('admin.borrowing.renewal_detail', compact('renewal'));
    }

    public function destroy(BorrowRequest $borrow)
    {
        // Only allow deletion of pending or rejected requests
        if (!in_array($borrow->status, ['pending', 'rejected'])) {
            return back()->withErrors("Không thể xóa yêu cầu mượn có trạng thái '{$borrow->status}'");
        }

        $borrowId = $borrow->id;
        $borrow->delete();

        return redirect()->route('admin.borrowing.index')
                       ->with('success', "Yêu cầu mượn #{$borrowId} đã được xóa thành công");
    }

    public function destroyRenewal(RenewalRequest $renewal)
    {
        // Only allow deletion of pending requests
        if ($renewal->status !== 'pending') {
            return back()->withErrors("Không thể xóa yêu cầu gia hạn có trạng thái '{$renewal->status}'");
        }

        $renewalId = $renewal->id;
        $renewal->delete();

        return redirect()->route('admin.borrowing.renewals')
                       ->with('success', "Yêu cầu gia hạn #{$renewalId} đã được xóa thành công");
    }

    public function destroyReturn(ReturnRequest $returnReq)
    {
        // Only allow deletion of pending requests
        if ($returnReq->status !== 'pending') {
            return back()->withErrors("Không thể xóa yêu cầu trả có trạng thái '{$returnReq->status}'");
        }

        $returnId = $returnReq->id;
        $returnReq->delete();

        return redirect()->route('admin.borrowing.returns')
                       ->with('success', "Yêu cầu trả #{$returnId} đã được xóa thành công");
    }
}

