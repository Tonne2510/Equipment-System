@extends('layouts.app')

@section('title', 'Duyệt Yêu Cầu Trả Thiết Bị')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Yêu Cầu Trả Thiết Bị</h3>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo nhân viên..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất Cả Trạng Thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã Duyệt</option>
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

<!-- Returns Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Yêu Cầu Trả Thiết Bị</h5>
    </div>
    <div class="card-body p-0">
        @if($returns->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="returns-table">
                    <thead class="table-light">
                        <tr>
                            <th>Người Mượn</th>
                            <th>Thiết Bị</th>
                            <th>Hạn Trả</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Gửi</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $returnReq)
                            @php $borrow = $returnReq->borrowRequest; @endphp
                            <tr>
                                <td>
                                    @if($borrow && $borrow->user)
                                        <strong>{{ $borrow->user->name }}</strong><br><small class="text-muted">{{ $borrow->user->email }}</small>
                                    @else
                                        <span class="text-danger"><i class="bi bi-exclamation-circle"></i> Dữ liệu không hợp lệ</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse($borrow->items->take(2) as $item)
                                        <span class="badge text-bg-primary me-1">{{ $item->model->name }}</span>
                                    @empty
                                        <span class="text-muted">-</span>
                                    @endforelse
                                    @if($borrow->items->count() > 2)
                                        <span class="badge text-bg-secondary">+{{ $borrow->items->count() - 2 }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrow->end_date < now())
                                        <span class="text-danger"><strong>{{ $borrow->end_date->format('d/m/Y') }}</strong> (quá hạn)</span>
                                    @else
                                        {{ $borrow->end_date->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $returnReq->status === 'pending' ? 'warning' : ($returnReq->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ $returnReq->status === 'pending' ? 'Chờ Duyệt' : ($returnReq->status === 'approved' ? 'Đã Duyệt' : 'Từ Chối') }}
                                    </span>
                                </td>
                                <td>{{ $returnReq->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    @if($returnReq->status === 'pending')
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.borrowing.return_detail', $returnReq) }}" class="btn btn-outline-info" title="Xem Chi Tiết Yêu Cầu Trả">
                                                <i class="bi bi-eye"></i> Chi Tiết
                                            </a>
                                            <button type="button" class="btn btn-success" title="Duyệt" data-bs-toggle="modal" data-bs-target="#approveModal{{ $returnReq->id }}">
                                                <i class="bi bi-check-lg"></i> Duyệt
                                            </button>
                                            <button type="button" class="btn btn-warning" title="Từ Chối" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $returnReq->id }}">
                                                <i class="bi bi-x-lg"></i> Từ Chối
                                            </button>
                                        </div>

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $returnReq->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Duyệt Trả Thiết Bị</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.borrowing.returns.approve', $returnReq) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group mb-3">
                                                                <label>Tình Trạng Thiết Bị</label>
                                                                <select class="form-select" name="condition" required>
                                                                    <option value="">-- Chọn Tình Trạng --</option>
                                                                    <option value="good">✓ Tốt</option>
                                                                    <option value="damaged">⚠️ Hỏng</option>
                                                                    <option value="lost">✗ Mất</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ghi Chú (nếu có)</label>
                                                                <textarea class="form-control" name="notes" rows="3" placeholder="VD: Vết xước nhẹ ở góc..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                            <button type="submit" class="btn btn-success">Duyệt</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $returnReq->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Từ Chối Yêu Cầu Trả</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.borrowing.returns.reject', $returnReq) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Lý Do Từ Chối</label>
                                                                <textarea class="form-control" name="reason" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                            <button type="submit" class="btn btn-danger">Từ Chối</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('admin.borrowing.return_detail', $returnReq) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted">
                <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                <p class="mt-2">Không có yêu cầu trả thiết bị nào</p>
            </div>
        @endif
    </div>
    @if($returns->hasPages())
    <div class="card-footer d-flex justify-content-center">
        {{ $returns->links() }}
    </div>
    @endif
</div>

@section('scripts')
<script>
// Initialize table manager for returns table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('returns-table');
});
</script>
@endsection
@endsection
