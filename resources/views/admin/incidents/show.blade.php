@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Sự Cố #{{ $report->id }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.incidents.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <!-- Main Info -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông Tin Sự Cố</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Thiết Bị:</strong>
                            <p>{{ $report->equipment?->model?->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Loại Sự Cố:</strong>
                            <p>
                                <span class="badge bg-info">
                                    {{ \App\Helpers\StatusHelper::getIncidentTypeBadge($report->incident_type, true) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Người Báo Cáo:</strong>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                @if($report->reportedBy?->avatar)
                                    <img src="{{ asset('storage/' . $report->reportedBy->avatar) }}" alt="{{ $report->reportedBy->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-muted"></i>
                                    </div>
                                @endif
                                <span>{{ $report->reportedBy?->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày Báo Cáo:</strong>
                            <p>{{ $report->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mức Độ:</strong>
                            <p>
                                @if($report->severity === 'high')
                                    <span class="badge bg-danger">Cao</span>
                                @elseif($report->severity === 'medium')
                                    <span class="badge bg-warning">Trung Bình</span>
                                @else
                                    <span class="badge bg-info">Thấp</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng Thái:</strong>
                            <p>
                                @if($report->status === 'open')
                                    <span class="badge bg-primary">Mở</span>
                                @elseif($report->status === 'assigned')
                                    <span class="badge bg-warning">Được Gán</span>
                                @elseif($report->status === 'in-progress')
                                    <span class="badge bg-info">Đang Xử Lý</span>
                                @else
                                    <span class="badge bg-success">Đã Giải Quyết</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Mô Tả:</strong>
                            <p>{{ $report->description }}</p>
                        </div>
                    </div>

                    @if($report->image_path && is_array($report->image_path) && count($report->image_path) > 0)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <strong>Hình Ảnh Đính Kèm:</strong>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                @foreach($report->image_path as $image)
                                <a href="{{ asset('storage/' . $image) }}" target="_blank" class="text-decoration-none">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Incident Image" style="max-width: 150px; height: 120px; object-fit: cover; border-radius: 5px; cursor: pointer; border: 1px solid #dee2e6;">
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Hành Động</h5>
                </div>
                <div class="card-body">
                    @if($report->status !== 'resolved')
                        <form method="POST" action="{{ route('admin.incidents.assign', $report) }}" class="mb-2">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Gán Cho Nhân Viên (Admin/Manager)</label>
                                <select name="assigned_to" class="form-select" required>
                                    <option value="">-- Chọn Nhân Viên --</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" {{ $report->assigned_to == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }} ({{ $member->role->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Gán</button>
                        </form>

                        <form method="POST" action="{{ route('admin.incidents.resolve', $report) }}" class="mb-2">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Ghi Chú Giải Quyết</label>
                                <textarea name="resolution_notes" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Giải Quyết</button>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <strong>✓ Sự cố đã được giải quyết</strong>
                        </div>
                    @endif
                </div>
            </div>

            @if($report->assignedTo)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Người Xử Lý</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2">
                            @if($report->assignedTo->avatar)
                                <img src="{{ asset('storage/' . $report->assignedTo->avatar) }}" alt="{{ $report->assignedTo->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="bi bi-person text-muted"></i>
                                </div>
                            @endif
                            <span>{{ $report->assignedTo->name }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($report->resolution_notes)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="mb-0">Ghi Chú Giải Quyết</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $report->resolution_notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
