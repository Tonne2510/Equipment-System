@extends('layouts.app')

@section('title', 'Chi Tiết Thiết Bị')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Chi Tiết Thiết Bị: {{ $equipment->serial_number }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
            <a href="{{ route('admin.equipment.edit', $equipment) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Chỉnh Sửa
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Basic Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Thông Tin Cơ Bản</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 text-center">
                            @if($equipment->image)
                                <img src="{{ asset('storage/' . $equipment->image) }}" alt="Thiết bị" class="img-fluid rounded" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded p-5">
                                    <i class="fas fa-laptop fa-5x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Model</th>
                                    <td><strong>{{ $equipment->model->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Thương Hiệu</th>
                                    <td>{{ $equipment->model->brand?->name ?? 'Chưa xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Danh Mục</th>
                                    <td>{{ $equipment->model->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Serial Number</th>
                                    <td><code>{{ $equipment->serial_number }}</code></td>
                                </tr>
                                <tr>
                                    <th>Trạng Thái</th>
                                    <td>
                                        <span class="badge bg-{{ $equipment->status === 'available' ? 'success' : 'danger' }}">
                                            {{ ucfirst(str_replace('_', ' ', $equipment->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày Mua:</strong> {{ $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : '-' }}</p>
                            <p><strong>Mã Tài Sản:</strong> {{ $equipment->asset_tag ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giá Mua:</strong> {{ $equipment->purchase_cost ? number_format($equipment->purchase_cost, 0) . ' VNĐ' : '-' }}</p>
                            <p><strong>Bảo Hành Đến:</strong> {{ $equipment->warranty_until ? $equipment->warranty_until->format('d/m/Y') : '-' }}</p>
                        </div>
                    </div>

                    @if($equipment->location)
                        <hr>
                        <p><strong>Vị Trí:</strong> {{ $equipment->location }}</p>
                    @endif

                    @if($equipment->notes)
                        <hr>
                        <p><strong>Ghi Chú:</strong></p>
                        <p>{{ $equipment->notes }}</p>
                    @endif

                    @if($equipment->specifications)
                        <hr>
                        <p><strong>Quy Cách Kỹ Thuật:</strong></p>
                        <div class="bg-light p-3 rounded">
                            <pre>{{ json_encode(json_decode($equipment->specifications), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status History -->
            @if($statusHistory->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">Lịch Sử Trạng Thái</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="timeline">
                            @foreach($statusHistory as $history)
                                <div class="timeline-item px-4 py-3">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $history->old_status)) }} → {{ ucfirst(str_replace('_', ' ', $history->new_status)) }}</strong>
                                        <span class="badge bg-secondary">{{ $history->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($history->reason)
                                        <p class="small text-muted mb-0">{{ $history->reason }}</p>
                                    @endif
                                    <p class="small text-muted mb-0">Thay đổi bởi: {{ $history->changedBy->name ?? 'N/A' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Statistics Sidebar -->
        <div class="col-md-4">
            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-white-50">THỐNG KÊ</h6>
                    <hr class="opacity-25">
                    <p class="mb-2">
                        <strong>Lần Mượn:</strong> {{ $borrowCount }} lần
                    </p>
                    <p class="mb-2">
                        <strong>Lần Bảo Trì:</strong> {{ $maintenanceCount }} lần
                    </p>
                    <p class="mb-0">
                        <strong>Sự Cố:</strong> {{ $incidentCount }} cái
                    </p>
                </div>
            </div>

            <!-- Recent Borrows -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">Mượn Gần Đây</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentBorrows as $borrow)
                            <a href="{{ route('admin.borrowing.show', $borrow) }}" class="list-group-item list-group-item-action small">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $borrow->user->name }}</strong>
                                    <span class="badge bg-secondary">{{ $borrow->status }}</span>
                                </div>
                                <small class="text-muted">{{ $borrow->start_date->format('d/m/Y') }} - {{ $borrow->end_date->format('d/m/Y') }}</small>
                            </a>
                        @empty
                            <div class="p-3 text-center text-muted small">Chưa có lần mượn</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-item {
        border-left: 3px solid #3b82f6;
        padding-left: 1rem !important;
    }
    .timeline-item:hover {
        background-color: #f8fafc;
    }
    pre {
        font-size: 0.85rem;
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endsection
