@extends('layouts.app')

@section('title', 'Chi Tiết Yêu Cầu Trả')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Yêu Cầu Trả #{{ $returnReq->id }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.borrowing.returns') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Status -->
            <div class="alert alert-info mb-4">
                <strong>Trạng Thái:</strong>
                <span class="badge bg-{{ $returnReq->status === 'pending' ? 'warning' : ($returnReq->status === 'approved' ? 'success' : 'danger') }} ms-2">
                    {{ ucfirst($returnReq->status) }}
                </span>
            </div>

            <!-- Borrow Request Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông Tin Yêu Cầu Mượn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Người Mượn</p>
                            <h6>{{ $returnReq->borrowRequest && $returnReq->borrowRequest->user ? $returnReq->borrowRequest->user->name : 'N/A' }}</h6>
                            <p class="text-muted small mt-3">Hạn Trả</p>
                            <h6>{{ $returnReq->borrowRequest && $returnReq->borrowRequest->end_date ? $returnReq->borrowRequest->end_date->format('d/m/Y') : 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Email</p>
                            <h6>{{ $returnReq->borrowRequest && $returnReq->borrowRequest->user ? $returnReq->borrowRequest->user->email : 'N/A' }}</h6>
                            <p class="text-muted small mt-3">Ngày Yêu Cầu</p>
                            <h6>{{ $returnReq->created_at ? $returnReq->created_at->format('d/m/Y H:i') : 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Return Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông Tin Trả</h5>
                </div>
                <div class="card-body">
                    @if($returnReq->reason)
                        <p class="text-muted small">Lý Do Trả</p>
                        <p class="mb-3">{{ $returnReq->reason }}</p>
                    @endif
                    @if($returnReq->notes)
                        <p class="text-muted small">Ghi Chú</p>
                        <p>{{ $returnReq->notes }}</p>
                    @endif
                    @if(!$returnReq->reason && !$returnReq->notes)
                        <p class="text-muted">Không có thông tin bổ sung</p>
                    @endif
                </div>
            </div>

            <!-- Equipment List -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thiết Bị ({{ $returnReq->borrowRequest->items->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Model</th>
                                    <th>Serial</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returnReq->borrowRequest->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->model->name }}</strong></td>
                                        <td><code>{{ $item->serial_number }}</code></td>
                                        <td><span class="badge bg-info">{{ ucfirst($item->status) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Sidebar -->
        <div class="col-md-4">
            @if($returnReq->status === 'pending')
                <div class="card border-left-warning mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">Xử Lý</h6>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="bi bi-check-circle"></i> Duyệt Trả
                        </button>
                        <button class="btn btn-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Từ Chối
                        </button>
                        <form method="POST" action="{{ route('admin.borrowing.return_destroy', $returnReq) }}" style="display: inline-block; width: 100%;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card border-left-secondary mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Hành Động</h6>
                    </div>
                    <div class="card-body">
                        @if($returnReq->status === 'approved')
                            <p class="text-muted small mb-2">✓ Đã Duyệt Trả</p>
                            @if($returnReq->notes)
                                <p class="text-muted small">Ghi Chú Duyệt:</p>
                                <p class="mb-3">{{ $returnReq->notes }}</p>
                            @endif
                            @if($returnReq->approved_at)
                                <p class="text-muted small">Duyệt Lúc:</p>
                                <p class="mb-3">{{ $returnReq->approved_at->format('d/m/Y H:i') }}</p>
                            @endif
                        @elseif($returnReq->status === 'rejected')
                            <p class="text-muted small mb-2">✗ Đã Từ Chối</p>
                            @if($returnReq->reason)
                                <p class="text-muted small">Lý Do Từ Chối:</p>
                                <p class="mb-3">{{ $returnReq->reason }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif

                <!-- Approve Modal -->
                <div class="modal fade" id="approveModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Duyệt Yêu Cầu Trả</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.borrowing.returns.approve', $returnReq) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group mb-3">
                                        <label>Tình Trạng Thiết Bị</label>
                                        <select class="form-select" name="condition" required>
                                            <option value="">-- Chọn --</option>
                                            <option value="good">✓ Tốt</option>
                                            <option value="damaged">⚠️ Hỏng</option>
                                            <option value="lost">✗ Mất</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú</label>
                                        <textarea class="form-control" name="notes" rows="3"></textarea>
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
                <div class="modal fade" id="rejectModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Từ Chối Yêu Cầu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.borrowing.returns.reject', $returnReq) }}">
                                @csrf
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Lý Do Từ Chối</label>
                                        <textarea class="form-control" name="reason" rows="4" required></textarea>
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

            @if($returnReq->approvedBy)
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Người Duyệt</h6>
                    </div>
                    <div class="card-body">
                        <p>{{ $returnReq->approvedBy->name }} - {{ $returnReq->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
</style>
@endsection
