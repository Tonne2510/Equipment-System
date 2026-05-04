@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .hero-section {
        background: var(--primary-gradient);
        border-radius: 16px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-box.danger { border-left-color: #ef4444; }
    .stat-box.warning { border-left-color: #f59e0b; }
    .stat-box.info { border-left-color: #3b82f6; }
    .stat-box.success { border-left-color: #10b981; }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 10px 0;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.2;
        float: right;
    }

    .alert-box {
        border-radius: 12px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
    }

    .alert-box.success {
        background: #ecfdf5;
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .alert-box.warning {
        background: #fffbeb;
        border-left: 4px solid #f59e0b;
        color: #78350f;
    }

    .alert-box.danger {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #7f1d1d;
    }

    .card-hover {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .equipment-card {
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .equipment-image {
        height: 200px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .equipment-image img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .equipment-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .equipment-badge.available {
        background: var(--success-gradient);
        color: white;
    }

    .equipment-badge.unavailable {
        background: var(--danger-gradient);
        color: white;
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -36px;
        top: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #667eea;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-button.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-button.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
    }

    .chart-container {
        position: relative;
        height: 250px;
        margin-bottom: 20px;
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1 class="h2 mb-2">Xin chào, {{ auth()->user()->name }}! 👋</h1>
        <p class="lead mb-0">Chào mừng trở lại hệ thống quản lý mượn thiết bị. Khám phá, mượn và quản lý các thiết bị bạn cần cho công việc hàng ngày.</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box">
            <i class="bi bi-box2 stat-icon"></i>
            <div class="stat-label">Đang Mượn</div>
            <div class="stat-number">{{ $myLoans->where('status', 'borrowed')->sum(function($loan) { return $loan->items->count(); }) }}</div>
            <small class="text-muted">Thiết bị hoạt động</small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box warning">
            <i class="bi bi-hourglass-split stat-icon"></i>
            <div class="stat-label">Chờ Duyệt</div>
            <div class="stat-number">{{ $pendingRequests }}</div>
            <small class="text-muted">Yêu cầu chưa xử lý</small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box danger">
            <i class="bi bi-exclamation-circle stat-icon"></i>
            <div class="stat-label">Vi Phạm</div>
            <div class="stat-number">{{ $violations }}</div>
            <small class="text-danger">Cần xử lý</small>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box info">
            <i class="bi bi-wallet2 stat-icon"></i>
            <div class="stat-label">Phí Chưa Trả</div>
            <div class="stat-number">{{ number_format($unpaidPenalties / 1000000, 1) }}M</div>
            <small class="text-muted">VND</small>
        </div>
    </div>
</div>

<!-- Alert Boxes -->
@if($activeLoan)
<div class="alert-box success">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-check-circle" style="font-size: 1.5rem;"></i>
        <div style="flex: 1;">
            <strong>Mượn Đang Hoạt Động</strong>
            <p class="mb-0 mt-1">Bạn đang mượn <strong>{{ $activeLoan->items->count() }} thiết bị</strong>, ngày trả dự kiến: <strong>{{ $activeLoan->end_date->format('d/m/Y') }}</strong></p>
        </div>
        <a href="{{ route('employee.borrowings.show', $activeLoan) }}" class="btn btn-sm btn-outline-success">Xem Chi Tiết</a>
    </div>
</div>
@endif

@if($violations > 0)
<div class="alert-box danger">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem;"></i>
        <div style="flex: 1;">
            <strong>Vi Phạm Cần Xử Lý</strong>
            <p class="mb-0 mt-1">Bạn có <strong>{{ $violations }} vi phạm</strong> với tổng phí chưa trả. Vui lòng xử lý sớm để tránh những hạn chế trong tương lai.</p>
        </div>
        <a href="#" class="btn btn-sm btn-outline-danger">Thanh Toán</a>
    </div>
</div>
@endif

@if($pendingRequests > 0)
<div class="alert-box warning">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-info-circle" style="font-size: 1.5rem;"></i>
        <div style="flex: 1;">
            <strong>Yêu Cầu Chờ Duyệt</strong>
            <p class="mb-0 mt-1">Bạn có <strong>{{ $pendingRequests }} yêu cầu mượn</strong> đang chờ duyệt. Vui lòng chờ phản hồi từ quản lý.</p>
        </div>
        <a href="{{ route('employee.borrowings.index') }}" class="btn btn-sm btn-outline-warning">Xem Yêu Cầu</a>
    </div>
</div>
@endif

<!-- Featured Equipment Section -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="section-title">
            <i class="bi bi-star-fill" style="color: #f59e0b;"></i> Thiết Bị Phổ Biến
        </h3>
        <a href="{{ route('employee.equipment.browse') }}" class="text-decoration-none" style="color: #667eea; font-weight: 500;">
            Xem Tất Cả <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    
    <div class="row g-4">
        @forelse($topEquipment ?? [] as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card card-hover equipment-card">
                <div class="equipment-image">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model->name }}">
                    @else
                        <i class="bi bi-box2" style="font-size: 3rem; color: #d1d5db;"></i>
                    @endif
                    <span class="equipment-badge {{ $item->status === 'available' ? 'available' : 'unavailable' }}">
                        <i class="bi {{ $item->status === 'available' ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                        {{ $item->status === 'available' ? 'Còn' : 'Hết' }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $item->model->name }}</h6>
                    <p class="small text-muted mb-2">
                        <i class="bi bi-tag"></i> {{ $item->model->category->name ?? 'N/A' }}
                    </p>
                    <p class="small text-muted mb-3">
                        <i class="bi bi-barcode"></i> SN: {{ $item->serial_number }}
                    </p>
                    @if($item->status === 'available')
                        <a href="{{ route('employee.equipment.show', $item) }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-hand-index"></i> Xem & Mượn
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary w-100" disabled>
                            <i class="bi bi-lock"></i> Không Có Sẵn
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div style="text-align: center; padding: 40px; background: #f9fafb; border-radius: 12px;">
                <i class="bi bi-inbox" style="font-size: 2rem; color: #d1d5db; display: block; margin-bottom: 10px;"></i>
                <p class="text-muted">Hiện không có thiết bị khả dụng</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Recent Borrowing History -->
<div class="mb-5">
    <h3 class="section-title">
        <i class="bi bi-clock-history"></i> Lịch Sử Mượn Gần Đây
    </h3>
    
    @if(isset($recentLoans) && $recentLoans->count() > 0)
    <div class="card card-hover">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Thiết Bị</th>
                        <th>Ngày Mượn</th>
                        <th>Ngày Trả Dự Kiến</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLoans as $loan)
                    <tr>
                        <td>
                            <strong>{{ $loan->items->pluck('model.name')->join(', ') ?? 'N/A' }}</strong>
                        </td>
                        <td>
                            <span class="text-muted">{{ $loan->start_date ? $loan->start_date->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $loan->end_date ? $loan->end_date->format('d/m/Y') : 'N/A' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($loan->status) {
                                    'borrowed' => 'info',
                                    'returned' => 'success',
                                    'pending' => 'warning',
                                    default => 'danger'
                                };
                                $statusText = match($loan->status) {
                                    'borrowed' => 'Đang Mượn',
                                    'returned' => 'Đã Trả',
                                    'pending' => 'Chờ Duyệt',
                                    default => 'Từ Chối'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <a href="{{ route('employee.borrowings.show', $loan) }}" class="btn btn-sm btn-outline-primary" title="Chi tiết">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div style="text-align: center; padding: 40px; background: #f9fafb; border-radius: 12px;">
        <i class="bi bi-inbox" style="font-size: 2rem; color: #d1d5db; display: block; margin-bottom: 10px;"></i>
        <p class="text-muted">Bạn chưa có lịch sử mượn nào</p>
        <a href="{{ route('employee.equipment.browse') }}" class="btn btn-primary btn-sm mt-3">
            <i class="bi bi-search"></i> Tìm Thiết Bị
        </a>
    </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card card-hover bg-gradient-light" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-color: transparent;">
            <div class="card-body text-center py-5">
                <h5 class="card-title mb-3" style="color: #1f2937;">Bạn Cần Thiết Bị Gì Không?</h5>
                <p class="text-muted mb-4">Khám phá bộ sưu tập đầy đủ các thiết bị có sẵn để mượn từ kho của chúng tôi</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('employee.equipment.browse') }}" class="action-button primary">
                        <i class="bi bi-search"></i> Tìm Thiết Bị
                    </a>
                    <a href="{{ route('employee.borrowings.create') }}" class="action-button primary">
                        <i class="bi bi-plus-circle"></i> Tạo Yêu Cầu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="card card-hover">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">
            <i class="bi bi-lightning"></i> Tùy Chọn Nhanh
        </h5>
    </div>
    <div class="card-body">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('employee.borrowings.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Tạo Yêu Cầu Mượn
            </a>
            <a href="{{ route('employee.borrowings.index') }}" class="btn btn-info">
                <i class="bi bi-list-check"></i> Xem Lịch Sử Mượn
            </a>
            <a href="{{ route('employee.equipment.browse') }}" class="btn btn-primary">
                <i class="bi bi-search"></i> Thiết Bị
            </a>
            <a href="{{ route('employee.incidents.create') }}" class="btn btn-warning">
                <i class="bi bi-exclamation-triangle"></i> Báo Cáo Sự Cố
            </a>
        </div>
    </div>
</div>
@endsection
