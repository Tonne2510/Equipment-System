@extends('layouts.app')

@section('title', 'Duyệt Yêu Cầu Mượn')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Quản Lý Yêu Cầu Mượn</h3>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên nhân viên..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất Cả Trạng Thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã Duyệt</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Đang Mượn</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Đã Trả</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ Chối</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Yêu Cầu Mượn</h5>
    </div>
    <div class="card-body p-0">
        @if($borrows->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="borrowing-table">
                    <thead class="table-light">
                        <tr>
                            <th>Người Mượn</th>
                            <th>Email</th>
                            <th>Thiết Bị</th>
                            <th>Ngày Mượn</th>
                            <th>Ngày Trả</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($borrows as $borrow)
                            <tr>
                                <td><strong>{{ $borrow->user?->name ?? 'N/A' }}</strong></td>
                                <td>{{ $borrow->user?->email ?? 'N/A' }}</td>
                                <td>
                                    @forelse($borrow->items->take(2) as $item)
                                        <span class="badge text-bg-primary me-1">{{ $item->model?->name ?? 'N/A' }}</span>
                                    @empty
                                        <span class="text-muted">Không có</span>
                                    @endforelse
                                    @if($borrow->items->count() > 2)
                                        <span class="badge text-bg-secondary">+{{ $borrow->items->count() - 2 }}</span>
                                    @endif
                                </td>
                                <td>{{ $borrow->start_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td>{{ $borrow->end_date?->format('d/m/Y') ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $borrow->status === 'pending' ? 'warning' : ($borrow->status === 'approved' ? 'info' : ($borrow->status === 'borrowed' ? 'success' : ($borrow->status === 'rejected' ? 'danger' : 'secondary'))) }}">
                                        @switch($borrow->status)
                                            @case('pending')
                                                Chờ Duyệt
                                                @break
                                            @case('approved')
                                                Đã Duyệt
                                                @break
                                            @case('borrowed')
                                                Đang Mượn
                                                @break
                                            @case('returned')
                                                Đã Trả
                                                @break
                                            @case('rejected')
                                                Từ Chối
                                                @break
                                            @default
                                                {{ ucfirst($borrow->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Quick Actions Dropdown -->
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.borrowing.show', $borrow) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                        @if($borrow->status === 'pending')
                                            <form method="POST" action="{{ route('admin.borrowing.approve', $borrow) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Phê duyệt yêu cầu này?');" title="Phê duyệt">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $borrow->id }}" title="Từ chối">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @elseif($borrow->status === 'approved')
                                            <form method="POST" action="{{ route('admin.borrowing.mark-borrowed', $borrow) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info" onclick="return confirm('Xác nhận đã giao thiết bị?');" title="Ghi nhận giao">
                                                    <i class="bi bi-arrow-right"></i>
                                                </button>
                                            </form>
                                        @elseif($borrow->status === 'borrowed')
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#returnModal{{ $borrow->id }}" title="Ghi nhận trả">
                                                <i class="bi bi-arrow-left"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted py-4">
                <p class="mb-0">Không có yêu cầu mượn nào phù hợp</p>
            </div>
        @endif
    </div>
    @if($borrows->count() > 0)
        <div class="card-footer d-flex justify-content-center">
            {{ $borrows->links() }}
        </div>
    @endif
</div>

<!-- Reject & Return Modals -->
@foreach($borrows as $borrow)
    @if($borrow->status === 'pending')
    <div class="modal fade" id="rejectModal{{ $borrow->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Từ Chối Yêu Cầu #{{ $borrow->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.borrowing.reject', $borrow) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason{{ $borrow->id }}" class="form-label">Lý Do Từ Chối <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="reason{{ $borrow->id }}" class="form-control" rows="4" placeholder="Vui lòng nhập lý do từ chối (ít nhất 10 ký tự)..." required></textarea>
                            <small class="form-text text-muted">Lý do này sẽ được gửi cho nhân viên</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Xác Nhận Từ Chối
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @if($borrow->status === 'borrowed')
    <div class="modal fade" id="returnModal{{ $borrow->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ghi Nhận Trả Thiết Bị #{{ $borrow->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.borrowing.mark-returned', $borrow) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="condition{{ $borrow->id }}" class="form-label">Tình Trạng Thiết Bị <span class="text-danger">*</span></label>
                            <select name="condition" id="condition{{ $borrow->id }}" class="form-select" required>
                                <option value="">-- Chọn Tình Trạng --</option>
                                <option value="good">Tốt</option>
                                <option value="damaged">Hư Hỏng</option>
                                <option value="lost">Mất Thiết Bị</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="damage_notes{{ $borrow->id }}" class="form-label">Ghi Chú</label>
                            <textarea name="damage_notes" id="damage_notes{{ $borrow->id }}" class="form-control" rows="3" placeholder="Ghi chú tình trạng hư hỏng (nếu có)..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Xác Nhận Trả
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach

<style>
.btn-group {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}
.btn-group form {
    margin: 0;
}
.btn-group .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}
</style>

@section('scripts')
<script>
// Initialize table manager for borrowing table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('borrowing-table');
});
</script>
@endsection
@endsection
