@extends('layouts.app')

@section('title', 'Chi Tiết Sự Cố')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Chi Tiết Báo Cáo Sự Cố #{{ $report->id }}</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.incidents.index') }}" class="btn btn-secondary">
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
                            <strong>Serial Number:</strong>
                            <p>{{ $report->equipment?->serial_number }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Loại Sự Cố:</strong>
                            <p>
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $report->incident_type)) }}
                                </span>
                            </p>
                        </div>
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
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Ngày Báo Cáo:</strong>
                            <p>{{ $report->created_at->format('d/m/Y H:i') }}</p>
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
                                @elseif($report->status === 'resolved')
                                    <span class="badge bg-success">Đã Giải Quyết</span>
                                @else
                                    <span class="badge bg-secondary">{{ $report->status }}</span>
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
                            <div class="d-flex gap-2 flex-wrap">
                                @foreach($report->image_path as $image)
                                <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Incident" style="max-width: 150px; border-radius: 5px; cursor: pointer;">
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Panel -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thông Tin Xử Lý</h5>
                </div>
                <div class="card-body">
                    @if($report->assignedTo)
                        <div class="mb-3">
                            <strong>Người Xử Lý:</strong>
                            <p>{{ $report->assignedTo->name }}</p>
                        </div>
                    @endif

                    @if($report->resolution_notes)
                        <div class="mb-3">
                            <strong>Ghi Chú Giải Quyết:</strong>
                            <p>{{ $report->resolution_notes }}</p>
                        </div>
                    @endif

                    @if($report->resolved_at)
                        <div class="mb-3">
                            <strong>Ngày Giải Quyết:</strong>
                            <p>{{ $report->resolved_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
