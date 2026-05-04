@extends('layouts.app')

@section('title', 'Incident Reports')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-8">
        <h3>Danh Sách Báo Cáo Sự Cố</h3>
    </div>
    <div class="col-sm-4 text-end">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="severity" class="form-select">
                    <option value="">-- Tất Cả Mức Độ --</option>
                    <option value="low" {{ request('severity') === 'low' ? 'selected' : '' }}>Thấp</option>
                    <option value="medium" {{ request('severity') === 'medium' ? 'selected' : '' }}>Trung Bình</option>
                    <option value="high" {{ request('severity') === 'high' ? 'selected' : '' }}>Cao</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất Cả Trạng Thái --</option>
                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Mở</option>
                    <option value="in-progress" {{ request('status') === 'in-progress' ? 'selected' : '' }}>Đang Xử Lý</option>
                    <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Đã Giải Quyết</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Incidents Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Báo Cáo</h5>
    </div>
    <div class="card-body p-0">
        @if($incidents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Thiết Bị</th>
                            <th>Loại Sự Cố</th>
                            <th>Báo Cáo Bởi</th>
                            <th>Mức Độ</th>
                            <th>Trạng Thái</th>
                            <th>Giao Cho</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $incident)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $incident->equipment?->model?->name ?? 'N/A' }}</strong>
                                        <br>
                                        <code class="small text-muted">{{ $incident->equipment?->serial_number ?? 'N/A' }}</code>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ \App\Helpers\StatusHelper::getIncidentTypeBadge($incident->incident_type, true) }}
                                    </span>
                                </td>
                                <td>{{ $incident->reportedBy?->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ \App\Helpers\StatusHelper::getSeverityColor($incident->severity) }}">
                                        {{ \App\Helpers\StatusHelper::getSeverityBadge($incident->severity, true) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ \App\Helpers\StatusHelper::getStatusColor($incident->status) }}">
                                        {{ \App\Helpers\StatusHelper::getStatusBadge($incident->status, true) }}
                                    </span>
                                </td>
                                <td>
                                    @if($incident->assignedTo)
                                        <span class="badge text-bg-light text-dark">{{ $incident->assignedTo->name }}</span>
                                    @else
                                        <span class="badge text-bg-warning">Chưa giao</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.incidents.show', $incident) }}" class="btn btn-outline-primary">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted py-4">
                <p class="mb-0">Không có báo cáo sự cố nào</p>
            </div>
        @endif
    </div>
    @if($incidents->count() > 0)
        <div class="card-footer d-flex justify-content-center">
            {{ $incidents->links() }}
        </div>
    @endif
</div>
@endsection
