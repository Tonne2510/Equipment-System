@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .stat-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .stat-label {
        font-size: 0.95rem;
        color: #666;
        font-weight: 500;
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.1;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
    }
    .chart-container {
        position: relative;
        height: 300px;
        margin-bottom: 20px;
    }
</style>

<!-- Header Section -->
<div class="mb-4">
    <h1 class="h3 mb-1" style="color: #2c3e50;">Dashboard Quản Lý Thiết Bị</h1>
    <p class="text-muted">Chào mừng bạn quay lại! Đây là tổng quan hệ thống ngày hôm nay.</p>
</div>

<!-- Key Statistics Row 1 -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-box2 stat-icon" style="color: #667eea;"></i>
                <p class="stat-label mb-2">Tổng Thiết Bị</p>
                <h2 class="stat-value mb-2">{{ $totalEquipment }}</h2>
                <small class="text-success"><i class="bi bi-arrow-up"></i> Hệ thống quản lý</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-check-circle-fill stat-icon" style="color: #10b981;"></i>
                <p class="stat-label mb-2">Thiết Bị Sẵn Sàng</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $availableEquipment }}</h2>
                <small class="text-muted">{{ round(($availableEquipment / $totalEquipment) * 100, 1) }}% tổng số</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-hand-index stat-icon" style="color: #f59e0b;"></i>
                <p class="stat-label mb-2">Đang Mượn</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $borrowedEquipment }}</h2>
                <small class="text-muted">Bản ghi hoạt động</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-exclamation-triangle stat-icon" style="color: #ef4444;"></i>
                <p class="stat-label mb-2">Quá Hạn</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $overdueCount }}</h2>
                <small class="text-danger">Cần xử lý ngay</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Trạng Thái Thiết Bị</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="equipmentStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-3">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Thống Kê Mượn</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="borrowingStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-gear-fill stat-icon" style="color: #3b82f6;"></i>
                <p class="stat-label mb-2">Bảo Trì</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $maintenanceEquipment }}</h2>
                <small class="text-muted">Đang bảo dưỡng</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-exclamation-circle stat-icon" style="color: #ff6b6b;"></i>
                <p class="stat-label mb-2">Hỏng Hóc</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #ff6b6b 0%, #dc2626 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $damagedEquipment }}</h2>
                <small class="text-danger">Cần sửa chữa</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-people-fill stat-icon" style="color: #8b5cf6;"></i>
                <p class="stat-label mb-2">Nhân Viên</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $totalEmployees }}</h2>
                <small class="text-muted">Tài khoản hoạt động</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card">
            <div class="card-body position-relative">
                <i class="bi bi-file-earmark-alert stat-icon" style="color: #ec4899;"></i>
                <p class="stat-label mb-2">Vi Phạm</p>
                <h2 class="stat-value mb-2" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">{{ $activeViolations }}</h2>
                <small class="text-muted">Cần xử lý</small>
            </div>
        </div>
    </div>
</div>

<!-- Pending Approvals & Incidents Row -->
<div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0"><i class="bi bi-clock-history"></i> Chờ Duyệt</h5>
            </div>
            <div class="card-body p-0">
                @if($pendingApprovals->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($pendingApprovals as $approval)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $approval->user->name }}</h6>
                                    <small class="text-muted">{{ $approval->items->count() }} thiết bị</small>
                                </div>
                                <span class="badge bg-warning">{{ $approval->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-check-circle"></i> Không có yêu cầu chờ duyệt
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header bg-light border-bottom">
                <h5 class="card-title mb-0"><i class="bi bi-alert-triangle"></i> Sự Cố Gần Đây</h5>
            </div>
            <div class="card-body p-0">
                @if($recentIncidents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentIncidents as $incident)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $incident->title ?? 'Không có tiêu đề' }}</h6>
                                    <small class="text-muted">{{ $incident->severity }}</small>
                                </div>
                                <span class="badge bg-danger">{{ $incident->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-check-circle"></i> Không có sự cố
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0"><i class="bi bi-lightning"></i> Hành Động Nhanh</h5>
    </div>
    <div class="card-body">
        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-circle"></i> Thêm Thiết Bị Mới
        </a>
        <a href="{{ route('admin.borrowing.index') }}" class="btn btn-info me-2">
            <i class="bi bi-hand-index"></i> Quản Lý Mượn
        </a>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-success me-2">
            <i class="bi bi-list-check"></i> Xem Tất Cả Thiết Bị
        </a>
        <a href="{{ route('admin.reports.utilization') }}" class="btn btn-warning">
            <i class="bi bi-graph-up"></i> Xem Báo Cáo
        </a>
    </div>
</div>

<!-- Top Equipment Section -->
@if($topEquipment->count() > 0)
<div class="card">
    <div class="card-header bg-light border-bottom">
        <h5 class="card-title mb-0"><i class="bi bi-star-fill"></i> Thiết Bị Được Mượn Nhiều Nhất</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($topEquipment as $item)
                @php
                    $equipment = \App\Models\EquipmentItem::find($item->equipment_item_id);
                @endphp
                @if($equipment)
                    <div class="col-md-4 col-lg-2">
                        <div class="card h-100 border-0 shadow-sm text-center hover" style="cursor: pointer; transition: all 0.3s;">
                            <div class="position-relative bg-light" style="height: 120px; overflow: hidden; border-radius: 8px 8px 0 0;">
                                @if($equipment->image)
                                    <img src="{{ asset('storage/' . $equipment->image) }}" alt="{{ $equipment->model->name }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="bi bi-box2 text-white" style="font-size: 2.5rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body p-3">
                                <h6 class="card-title small mb-2 text-truncate">{{ $equipment->model->name }}</h6>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <i class="bi bi-hand-index text-warning"></i>
                                    <strong style="color: #667eea;">{{ $item->borrow_count }}</strong>
                                    <span class="text-muted small">lượt</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Equipment Status Chart
    const statusCtx = document.getElementById('equipmentStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sẵn Sàng', 'Đang Mượn', 'Bảo Trì', 'Hỏng Hóc'],
                datasets: [{
                    data: [{{ $availableEquipment }}, {{ $borrowedEquipment }}, {{ $maintenanceEquipment }}, {{ $damagedEquipment }}],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#3b82f6',
                        '#ef4444'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    }

    // Borrowing Stats Chart
    const borrowCtx = document.getElementById('borrowingStatsChart');
    if (borrowCtx) {
        new Chart(borrowCtx, {
            type: 'bar',
            data: {
                labels: ['Chờ Duyệt', 'Đang Mượn', 'Quá Hạn'],
                datasets: [{
                    label: 'Số Lượng',
                    data: [{{ $pendingRequests }}, {{ $activeBorrows }}, {{ $overdueCount }}],
                    backgroundColor: [
                        '#fbbf24',
                        '#60a5fa',
                        '#f87171'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        max: Math.max({{ $pendingRequests }}, {{ $activeBorrows }}, {{ $overdueCount }}) + 5
                    }
                }
            }
        });
    }
});
</script>
@endsection
