@extends('layouts.app')

@section('title', 'Report: Sử Dụng Thiết Bị')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-8">
        <h3>Báo Cáo Sử Dụng Thiết Bị</h3>
    </div>
    <div class="col-sm-4 text-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<!-- Date Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports.utilization') }}" class="row g-2">
            <div class="col-md-5">
                <label class="form-label">Từ Ngày</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date', now()->subMonths(3)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Đến Ngày</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Top Equipment Card -->
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Top 10 Thiết Bị Được Mượn Nhiều Nhất</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">Xếp Hạng</th>
                        <th>Model</th>
                        <th>Serial Number</th>
                        <th class="text-center">Lần Mượn</th>
                        <th class="text-center">Tổng Thời Gian</th>
                        <th>Tỷ Lệ Sử Dụng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topEquipment as $index => $item)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>{{ $item->model->name }}</td>
                            <td><code class="small">{{ $item->serial_number }}</code></td>
                            <td class="text-center"><span class="badge text-bg-primary">{{ $item->borrow_count ?? 0 }}</span></td>
                            <td class="text-center">{{ abs($item->total_days ?? 0) }} ngày</td>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: {{ min(abs($item->total_days ?? 0) / 90 * 100, 100) }}%">
                                        {{ number_format(min(abs($item->total_days ?? 0) / 90 * 100, 100), 0) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Equipment Status & Category Stats -->
<div class="row">
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Phân Bổ Trạng Thái Thiết Bị</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Sẵn Sàng</strong>
                        <span class="badge text-bg-success">{{ $statusCount['available'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Đang Mượn</strong>
                        <span class="badge text-bg-info">{{ $statusCount['borrowed'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Bảo Trì</strong>
                        <span class="badge text-bg-warning">{{ $statusCount['maintenance'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Hỏng</strong>
                        <span class="badge text-bg-danger">{{ $statusCount['damaged'] ?? 0 }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>Mất</strong>
                        <span class="badge text-bg-secondary">{{ $statusCount['lost'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Danh Mục Có Nhiều Thiết Bị</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($categoryStats as $cat)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $cat->name }}</strong>
                                    <br>
                                    <small class="text-muted">Mượn: {{ $cat->borrow_count }} lần</small>
                                </div>
                                <span class="badge text-bg-primary">{{ $cat->items_count }} cái</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted">Chưa có dữ liệu</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
