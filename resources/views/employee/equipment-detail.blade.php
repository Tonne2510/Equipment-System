@extends('layouts.app')

@section('title', 'Chi Tiết Thiết Bị')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Chi Tiết Thiết Bị: {{ $equipment->serial_number }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.equipment.browse') }}" class="btn btn-secondary me-2">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
            @if($equipment->status === 'available')
                <a href="{{ route('employee.borrowings.create', ['equipment' => $equipment->id]) }}" class="btn btn-primary">
                    <i class="bi bi-circle"></i> Mượn Thiết Bị
                </a>
            @else
                <button class="btn btn-secondary" disabled>
                    <i class="bi bi-lock"></i> Không Sẵn Sàng
                </button>
            @endif
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
                                    <i class="bi bi-laptop fa-5x text-muted"></i>
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
                                            {{ match($equipment->status) {
                                                'available' => 'Sẵn Sàng',
                                                'borrowed' => 'Đang Mượn',
                                                'maintenance' => 'Bảo Trì',
                                                'damaged' => 'Hỏng',
                                                'lost' => 'Mất',
                                                default => ucfirst(str_replace('_', ' ', $equipment->status))
                                            } }}
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
                        <div class="col-md-6">
                            <p><strong>Giá Mua:</strong> {{ $equipment->purchase_price ? number_format($equipment->purchase_price, 0) . ' VNĐ' : '-' }}</p>
                            <p><strong>Bảo Hành:</strong> {{ $equipment->warranty_months ?? '-' }} tháng</p>
                        </div>
                    </div>

                    @if($equipment->description)
                        <hr>
                        <p><strong>Mô Tả:</strong></p>
                        <p>{{ $equipment->description }}</p>
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
            @if($equipment->statusHistory && $equipment->statusHistory->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">Lịch Sử Thay Đổi Trạng Thái</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày/Giờ</th>
                                        <th>Trạng Thái</th>
                                        <th>Ghi Chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->statusHistory->take(10) as $history)
                                        <tr>
                                            <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ match($history->status) {
                                                    'available' => 'success',
                                                    'borrowed' => 'info',
                                                    'maintenance' => 'warning',
                                                    'damaged' => 'danger',
                                                    'lost' => 'dark',
                                                    default => 'secondary'
                                                } }}">
                                                    {{ match($history->status) {
                                                        'available' => 'Sẵn Sàng',
                                                        'borrowed' => 'Đang Mượn',
                                                        'maintenance' => 'Bảo Trì',
                                                        'damaged' => 'Hỏng',
                                                        'lost' => 'Mất',
                                                        default => ucfirst(str_replace('_', ' ', $history->status))
                                                    } }}
                                                </span>
                                            </td>
                                            <td>{{ $history->notes ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Borrows -->
            @if($equipment->borrowRequests && $equipment->borrowRequests->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">Lịch Sử Mượn Gần Đây</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Người Mượn</th>
                                        <th>Ngày Mượn</th>
                                        <th>Ngày Trả Dự Kiến</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($equipment->borrowRequests->take(5) as $borrow)
                                        <tr>
                                            <td>{{ $borrow->user->name }}</td>
                                            <td>{{ $borrow->start_date ? $borrow->start_date->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $borrow->end_date ? $borrow->end_date->format('d/m/Y') : '-' }}</td>
                                            <td>
                                                @if($borrow->status === 'returned')
                                                    <span class="badge bg-success">Đã Trả</span>
                                                @elseif($borrow->status === 'borrowed')
                                                    <span class="badge bg-warning">Đang Mượn</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($borrow->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Thông Tin Bổ Sung</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng Thái:</strong>
                        <p>
                            <span class="badge bg-{{ $equipment->status === 'available' ? 'success' : 'danger' }} p-2">
                                {{ match($equipment->status) {
                                    'available' => 'Sẵn Sàng Mượn',
                                    'borrowed' => 'Đang Được Mượn',
                                    'maintenance' => 'Đang Bảo Trì',
                                    'damaged' => 'Hỏng Cần Sửa',
                                    'lost' => 'Mất Tích',
                                    default => ucfirst(str_replace('_', ' ', $equipment->status))
                                } }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Số Lần Mượn:</strong>
                        <p>{{ $equipment->borrowHistory ? count($equipment->borrowHistory) : 0 }} lần</p>
                    </div>

                    <div class="mb-3">
                        <strong>Ngày Mua:</strong>
                        <p>{{ $equipment->purchase_date ? $equipment->purchase_date->format('d/m/Y') : '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Bảo Hành:</strong>
                        <p>{{ $equipment->warranty_months ?? '-' }} tháng</p>
                    </div>

                    @if($equipment->status === 'available')
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle"></i> Thiết bị này có sẵn để mượn
                        </div>
                        <a href="{{ route('employee.borrowings.create', ['equipment' => $equipment->id]) }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Tạo Yêu Cầu Mượn
                        </a>
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Thiết bị này hiện không sẵn sàng mượn
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
