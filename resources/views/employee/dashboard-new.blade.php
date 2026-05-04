@extends('layouts.app')

@section('title', 'Employee Dashboard')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-primary text-white rounded-lg p-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="row align-items-center">
        <div class="col-lg-8">
            <h1 class="mb-2">Xin Chào, {{ auth()->user()->name }}! 👋</h1>
            <p class="lead mb-0">Chào mừng đến hệ thống quản lý mượn thiết bị. Khám phá và mượn các thiết bị bạn cần cho công việc.</p>
        </div>
        <div class="col-lg-4 text-end">
            <div class="display-4 text-white-50">📦</div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Đang Mượn</p>
                        <h3 class="mb-0">{{ $myLoans->where('status', 'borrowed')->count() }}</h3>
                    </div>
                    <span class="badge bg-primary rounded-pill p-2">
                        <i class="bi bi-box2"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Chờ Duyệt</p>
                        <h3 class="mb-0">{{ $pendingRequests }}</h3>
                    </div>
                    <span class="badge bg-warning rounded-pill p-2">
                        <i class="bi bi-clock"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Vi Phạm</p>
                        <h3 class="mb-0">{{ $violations }}</h3>
                    </div>
                    <span class="badge bg-danger rounded-pill p-2">
                        <i class="bi bi-exclamation-triangle"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Phí Chưa Trả</p>
                        <h3 class="mb-0">{{ number_format($unpaidPenalties / 1000000, 1) }}M</h3>
                    </div>
                    <span class="badge bg-info rounded-pill p-2">
                        <i class="bi bi-wallet2"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Alerts -->
@if($activeLoan)
<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex">
        <i class="bi bi-info-circle me-2 flex-shrink-0"></i>
        <div>
            <strong>Mượn Hoạt Động</strong> - Bạn đang mượn <strong>{{ $activeLoan->items->count() }} thiết bị</strong>, ngày trả dự kiến: <strong>{{ $activeLoan->end_date->format('d/m/Y') }}</strong>
            <a href="{{ route('employee.borrowings.show', $activeLoan) }}" class="alert-link ms-2">Xem Chi Tiết →</a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($violations > 0)
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex">
        <i class="bi bi-exclamation-triangle me-2 flex-shrink-0"></i>
        <div>
            Bạn có <strong>{{ $violations }} vi phạm</strong> và phí chưa trả. <a href="#" class="alert-link">Thanh toán ngay</a>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Featured Equipment Section -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">⭐ Thiết Bị Phổ Biến</h4>
        <a href="{{ route('employee.equipment.browse') }}" class="text-decoration-none">Xem tất cả →</a>
    </div>
    
    <div class="row g-4">
        @foreach($featuredEquipment ?? [] as $item)
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                <div class="position-relative">
                    <div class="bg-light rounded-top d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                        @else
                            <div class="text-center text-muted">
                                <i class="bi bi-box2" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    <span class="badge bg-{{ $item->status === 'available' ? 'success' : 'danger' }} position-absolute top-0 end-0 m-2">
                        {{ $item->status === 'available' ? 'Còn' : 'Hết' }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="card-title mb-1">{{ $item->model->name }}</h6>
                    <p class="small text-muted mb-2">{{ $item->model->category->name }}</p>
                    <p class="small mb-3">S/N: {{ $item->serial_number }}</p>
                    @if($item->status === 'available')
                        <a href="{{ route('employee.borrowings.create', ['equipment' => $item->id]) }}" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-lg"></i> Mượn
                        </a>
                    @else
                        <button class="btn btn-sm btn-secondary w-100" disabled>Không Có Sẵn</button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Recent Loans -->
<div class="mb-5">
    <h4 class="mb-4">📦 Lịch Sử Mươn Gần Đây</h4>
    @if($recentLoans->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover">
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
                        <strong>{{ $loan->items->pluck('model.name')->join(', ') }}</strong>
                    </td>
                    <td>{{ $loan->start_date->format('d/m/Y') }}</td>
                    <td>{{ $loan->end_date->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $loan->status === 'borrowed' ? 'info' : 
                            ($loan->status === 'returned' ? 'success' : 
                            ($loan->status === 'pending' ? 'warning' : 'danger'))
                        }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('employee.borrowings.show', $loan) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="alert alert-secondary" role="alert">
        <i class="bi bi-inbox"></i> Bạn chưa có lịch sử mượn nào
    </div>
    @endif
</div>

<!-- Call to Action -->
<div class="card border-0 bg-gradient-light mb-4" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
    <div class="card-body text-center py-5">
        <h5 class="card-title mb-3">Cần Thiết Bị Gì Không?</h5>
        <p class="text-muted mb-4">Khám phá bộ sưu tập đầy đủ các thiết bị có sẵn để mượn</p>
        <a href="{{ route('employee.equipment.browse') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-search"></i> Tìm Thiết Bị
        </a>
    </div>
</div>

<style>
.hover-shadow {
    transition: box-shadow 0.3s ease;
}
.hover-shadow:hover {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-light {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}
</style>
@endsection
