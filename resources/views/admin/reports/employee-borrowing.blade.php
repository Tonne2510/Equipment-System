@extends('layouts.app')

@section('title', 'Báo Cáo Mượn Thiết Bị Theo Nhân Viên')

@section('content')
<style>
    .stat-card {
        border-radius: 12px;
        background: white;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #667eea;
        margin-bottom: 15px;
    }
    .stat-card.success { border-left-color: #10b981; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.danger { border-left-color: #ef4444; }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 10px 0;
    }
    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
    }
</style>

<!-- Header -->
<div class="row mb-4">
    <div class="col-sm-8">
        <h3 class="h3 mb-2">Báo Cáo Mượn Thiết Bị Theo Nhân Viên</h3>
        <p class="text-muted">Liệt kê chi tiết các phiếu mượn theo nhân viên trong khoảng thời gian được chọn</p>
    </div>
    <div class="col-sm-4 text-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="stat-card success">
            <div class="stat-label">Tổng Phiếu Mượn</div>
            <div class="stat-value">{{ $stats['total_borrows'] }}</div>
            <small class="text-muted">Trong khoảng thời gian</small>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card">
            <div class="stat-label">Tổng Thiết Bị Mượn</div>
            <div class="stat-value">{{ $stats['total_items_borrowed'] }}</div>
            <small class="text-muted">Số lượng thiết bị</small>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="stat-card danger">
            <div class="stat-label">Phiếu Quá Hạn</div>
            <div class="stat-value">{{ $stats['overdue_count'] }}</div>
            <small class="text-danger">Cần xử lý</small>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">Bộ Lọc</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.employee-borrowing') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Từ Ngày</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Đến Ngày</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Nhân Viên</label>
                <select name="employee_id" class="form-select">
                    <option value="">-- Tất Cả Nhân Viên --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Borrowing List -->
<div class="card">
    <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0"><i class="bi bi-list-check"></i> Danh Sách Phiếu Mượn</h5>
    </div>
    <div class="card-body p-0">
        @if($borrowings->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nhân Viên</th>
                        <th>Thiết Bị</th>
                        <th style="width: 110px;">Ngày Mượn</th>
                        <th style="width: 110px;">Ngày Trả</th>
                        <th style="width: 90px;">Trạng Thái</th>
                        <th style="width: 100px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowings as $borrow)
                    <tr>
                        <td>
                            <strong class="text-primary">#{{ $borrow->id }}</strong>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($borrow->user->avatar)
                                    <img src="{{ asset('storage/' . $borrow->user->avatar) }}" alt="{{ $borrow->user->name }}" 
                                         class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 32px; height: 32px;">
                                        <i class="bi bi-person text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $borrow->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $borrow->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                @foreach($borrow->items->take(2) as $item)
                                    <span class="badge bg-info small">
                                        {{ $item->model->name }} (SN: {{ $item->serial_number }})
                                    </span>
                                @endforeach
                                @if($borrow->items->count() > 2)
                                    <span class="badge bg-secondary small">
                                        +{{ $borrow->items->count() - 2 }} thiết bị khác
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <small class="text-nowrap">
                                {{ $borrow->start_date ? $borrow->start_date->format('d/m/Y') : '-' }}
                            </small>
                        </td>
                        <td>
                            <small class="text-nowrap">
                                {{ $borrow->end_date ? $borrow->end_date->format('d/m/Y') : '-' }}
                            </small>
                        </td>
                        <td>
                            @php
                                $statusBg = match($borrow->status) {
                                    'pending' => 'warning',
                                    'approved' => 'info',
                                    'borrowed' => 'success',
                                    'returned' => 'secondary',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                                $statusText = match($borrow->status) {
                                    'pending' => 'Chờ Duyệt',
                                    'approved' => 'Đã Duyệt',
                                    'borrowed' => 'Đang Mượn',
                                    'returned' => 'Đã Trả',
                                    'cancelled' => 'Đã Huỷ',
                                    default => ucfirst($borrow->status)
                                };
                                
                                // Check if overdue
                                $isOverdue = $borrow->status === 'borrowed' && $borrow->end_date && $borrow->end_date < now();
                            @endphp
                            <span class="badge bg-{{ $statusBg }}">
                                {{ $statusText }}
                                @if($isOverdue)
                                    <i class="bi bi-exclamation-circle"></i>
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.borrowing.show', $borrow->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center p-3 border-top">
            <small class="text-muted">
                @if($borrowings->count() > 0)
                    Hiển thị {{ ($borrowings->currentPage() - 1) * $borrowings->perPage() + 1 }} - {{ min($borrowings->currentPage() * $borrowings->perPage(), $borrowings->total()) }} trong {{ $borrowings->total() }} phiếu
                @else
                    Không có phiếu mượn
                @endif
            </small>
            <nav>
                {{ $borrowings->links('pagination::bootstrap-5') }}
            </nav>
        </div>
        @else
        <div class="p-5 text-center">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #d1d5db; display: block; margin-bottom: 10px;"></i>
            <p class="text-muted mb-0">Không có dữ liệu phù hợp với bộ lọc được chọn</p>
        </div>
        @endif
    </div>
</div>

<!-- Summary by Status -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Phân Bố Theo Trạng Thái</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $statusSummary = $borrowings->groupBy('status');
                    @endphp
                    @foreach(['pending' => 'Chờ Duyệt', 'approved' => 'Đã Duyệt', 'borrowed' => 'Đang Mượn', 'returned' => 'Đã Trả', 'cancelled' => 'Đã Huỷ'] as $key => $label)
                        @php $count = $statusSummary->get($key, collect())->count(); @endphp
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>{{ $label }}</strong>
                            <span class="badge bg-secondary">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Top 10 Nhân Viên Mượn Nhiều Nhất</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @php
                        $topEmployees = $borrowings->groupBy('user_id')
                            ->map(fn($group) => ['user' => $group->first()->user, 'count' => $group->count()])
                            ->sortByDesc('count')
                            ->take(10);
                    @endphp
                    @foreach($topEmployees as $emp)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $emp['user']->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $emp['user']->email }}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $emp['count'] }} phiếu</span>
                        </div>
                    @endforeach
                    @if($topEmployees->isEmpty())
                        <div class="p-3 text-center text-muted">Không có dữ liệu</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
