@extends('layouts.app')

@section('title', 'Duyệt Yêu Cầu Gia Hạn')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Yêu Cầu Gia Hạn Thiết Bị</h3>
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

<!-- Renewals Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Yêu Cầu Gia Hạn</h5>
    </div>
    <div class="card-body p-0">
        @if($renewals->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="renewals-table">
                    <thead class="table-light">
                        <tr>
                            <th>Người Mượn</th>
                            <th>Thiết Bị</th>
                            <th>Hạn Cũ</th>
                            <th>Hạn Mới</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Gửi</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($renewals as $renewal)
                            @php $borrow = $renewal->borrowRequest; @endphp
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
                                <td>{{ $borrow->end_date->format('d/m/Y') }}</td>
                                <td><strong class="text-primary">{{ $renewal->new_end_date->format('d/m/Y') }}</strong></td>
                                <td>
                                    <span class="badge text-bg-{{ $renewal->status === 'pending' ? 'warning' : ($renewal->status === 'approved' ? 'success' : 'danger') }}">
                                        {{ $renewal->status === 'pending' ? 'Chờ Duyệt' : ($renewal->status === 'approved' ? 'Đã Duyệt' : 'Từ Chối') }}
                                    </span>
                                </td>
                                <td>{{ $renewal->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.borrowing.renewal_detail', $renewal) }}" class="btn btn-info" title="Chi Tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($renewal->status === 'pending')
                                            <form method="POST" action="{{ route('admin.borrowing.renewals.approve', $renewal) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success" title="Duyệt" onclick="return confirm('Duyệt gia hạn?');">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" title="Từ Chối" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $renewal->id }}">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if($renewal->status === 'pending')
                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $renewal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Từ Chối Yêu Cầu Gia Hạn</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.borrowing.renewals.reject', $renewal) }}">
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
                <p class="mt-2">Không có yêu cầu gia hạn nào</p>
            </div>
        @endif
    </div>
    @if($renewals->hasPages())
    <div class="card-footer d-flex justify-content-center">
        {{ $renewals->links() }}
    </div>
    @endif
</div>

@section('scripts')
<script>
// Initialize table manager for renewals table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('renewals-table');
});
</script>
@endsection
@endsection
