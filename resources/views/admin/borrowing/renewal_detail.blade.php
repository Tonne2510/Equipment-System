@extends('layouts.app')

@section('title', 'Chi Tiết Yêu Cầu Gia Hạn')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Yêu Cầu Gia Hạn #{{ $renewal->id }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.borrowing.renewals') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Status -->
            <div class="alert alert-info mb-4">
                <strong>Trạng Thái:</strong>
                <span class="badge bg-{{ $renewal->status === 'pending' ? 'warning' : ($renewal->status === 'approved' ? 'success' : 'danger') }} ms-2">
                    {{ ucfirst($renewal->status) }}
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
                            <h6>{{ $renewal->borrowRequest && $renewal->borrowRequest->user ? $renewal->borrowRequest->user->name : 'N/A' }}</h6>
                            <p class="text-muted small mt-3">Hạn Cũ</p>
                            <h6>{{ $renewal->borrowRequest && $renewal->borrowRequest->end_date ? $renewal->borrowRequest->end_date->format('d/m/Y') : 'N/A' }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Email</p>
                            <h6>{{ $renewal->borrowRequest && $renewal->borrowRequest->user ? $renewal->borrowRequest->user->email : 'N/A' }}</h6>
                            <p class="text-muted small mt-3">Ngày Yêu Cầu</p>
                            <h6>{{ $renewal->created_at ? $renewal->created_at->format('d/m/Y H:i') : 'N/A' }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Renewal Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông Tin Gia Hạn</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small">Hạn Mới Yêu Cầu</p>
                            <h6 class="text-primary"><strong>{{ $renewal->new_end_date->format('d/m/Y') }}</strong></h6>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small">Thêm Số Ngày</p>
                            <h6>{{ abs($renewal->new_end_date->diffInDays($renewal->borrowRequest->end_date)) }} ngày</h6>
                        </div>
                    </div>
                    @if($renewal->reason)
                        <hr>
                        <p class="text-muted small">Lý Do Gia Hạn</p>
                        <p>{{ $renewal->reason }}</p>
                    @endif
                </div>
            </div>

            <!-- Equipment List -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thiết Bị ({{ $renewal->borrowRequest->items->count() }})</h5>
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
                                @foreach($renewal->borrowRequest->items as $item)
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
            @if($renewal->status === 'pending')
                <div class="card border-left-warning mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">Xử Lý</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.borrowing.renewals.approve', $renewal) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle"></i> Duyệt Gia Hạn
                            </button>
                        </form>
                        <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle"></i> Từ Chối
                        </button>
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
                            <form method="POST" action="{{ route('admin.borrowing.renewals.reject', $renewal) }}">
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

            @if($renewal->approvedBy)
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Người Duyệt</h6>
                    </div>
                    <div class="card-body">
                        <p>{{ $renewal->approvedBy->name }} - {{ $renewal->updated_at->format('d/m/Y H:i') }}</p>
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
