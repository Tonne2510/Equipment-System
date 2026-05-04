@extends('layouts.app')

@section('title', 'Lịch Sử Mượn')

@section('content')
<!-- Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">📦 Lịch Sử Mượn Thiết Bị</h2>
            <p class="text-muted mb-0">Quản lý và theo dõi các yêu cầu mượn thiết bị của bạn</p>
        </div>
        <a href="{{ route('employee.equipment.browse') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Mượn Thiết Bị Mới
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-4">
    <ul class="nav nav-pills border-bottom pb-2 flex-wrap" role="tablist">
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == '' || request('status') == 'all' ? 'active bg-secondary text-white' : 'bg-secondary text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index') }}">
                <i class="bi bi-list"></i> Tất Cả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'pending' ? 'active bg-warning text-dark' : 'bg-warning text-dark opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'pending']) }}">
                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'borrowed' ? 'active bg-primary text-white' : 'bg-primary text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'borrowed']) }}">
                <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'renewal_requested' ? 'active bg-info text-white' : 'bg-info text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'renewal_requested']) }}">
                <i class="bi bi-arrow-repeat"></i> Gia Hạn
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'return_requested' ? 'active bg-warning text-dark' : 'bg-warning text-dark opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'return_requested']) }}">
                <i class="bi bi-reply-fill"></i> Yêu Cầu Trả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'returned' ? 'active bg-success text-white' : 'bg-success text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'returned']) }}">
                <i class="bi bi-check-circle"></i> Đã Trả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'rejected' ? 'active bg-danger text-white' : 'bg-danger text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'rejected']) }}">
                <i class="bi bi-x-circle"></i> Từ Chối
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link {{ request('status') == 'cancelled' ? 'active bg-secondary text-white' : 'bg-secondary text-white opacity-50' }}" 
               href="{{ route('employee.borrowings.index', ['status' => 'cancelled']) }}">
                <i class="bi bi-slash-circle"></i> Đã Hủy
            </a>
        </li>
    </ul>
</div>

<!-- Search Form -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="🔍 Tìm theo tên thiết bị..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('employee.borrowings.index') }}" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="bi bi-x-lg"></i> Xóa
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Borrow Requests -->
@if($borrowings->count() > 0)
    @foreach($borrowings as $request)
    <div class="card border-0 shadow-sm mb-3 borrow-card hover-shadow">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <!-- Left - Status & Equipment Images -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <span class="badge text-white p-2 w-100 text-center {{ 
                            $request->status === 'borrowed' ? 'bg-primary' :
                            ($request->status === 'pending' ? 'bg-warning' :
                            ($request->status === 'returned' ? 'bg-success' :
                            ($request->status === 'rejected' ? 'bg-danger' :
                            ($request->status === 'renewal_requested' ? 'bg-info' :
                            ($request->status === 'return_requested' ? 'bg-warning' :
                            ($request->status === 'cancelled' ? 'bg-secondary' : 'bg-secondary'))))))
                        }}">
                            @if($request->status === 'borrowed')
                                <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                            @elseif($request->status === 'pending')
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                            @elseif($request->status === 'returned')
                                <i class="bi bi-check-circle"></i> Đã Trả
                            @elseif($request->status === 'rejected')
                                <i class="bi bi-x-circle"></i> Từ Chối
                            @elseif($request->status === 'renewal_requested')
                                <i class="bi bi-arrow-repeat"></i> Yêu Cầu Gia Hạn
                            @elseif($request->status === 'return_requested')
                                <i class="bi bi-reply-fill"></i> Yêu Cầu Trả
                            @elseif($request->status === 'cancelled')
                                <i class="bi bi-slash-circle"></i> Đã Hủy
                            @else
                                <i class="bi bi-question-circle"></i> {{ ucfirst($request->status) }}
                            @endif
                        </span>
                    </div>
                    
                    <!-- Equipment Images -->
                    <div class="equipment-images">
                        @forelse($request->items->take(2) as $item)
                            <div class="mb-2">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model->name }}" class="img-fluid rounded" style="max-height: 80px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded p-3 text-center" style="height: 80px; display: flex; align-items: center;">
                                        <small class="text-muted">Không có ảnh</small>
                                    </div>
                                @endif
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>

                <!-- Center - Equipment & Info -->
                <div class="col-md-4">
                    <div class="mb-2">
                        <small class="text-muted">Yêu cầu #{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</small>
                    </div>
                    
                    <!-- Equipment List -->
                    <h6 class="mb-3"><strong>{{ $request->items->count() }} Thiết Bị</strong></h6>
                    <div class="equipment-list" style="max-height: 120px; overflow-y: auto;">
                        @foreach($request->items as $item)
                        <div class="mb-2 pb-2 border-bottom">
                            <strong class="text-dark small d-block">{{ $item->model->name }}</strong>
                            <small class="text-muted">S/N: <code>{{ $item->serial_number }}</code></small>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Center-Right - Dates & Time -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="text-muted d-block">📅 Ngày Mượn</small>
                        <strong>{{ $request->start_date->format('d/m/Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">📅 Ngày Dự Trả</small>
                        <strong>{{ $request->end_date->format('d/m/Y') }}</strong>
                    </div>
                    
                    @if($request->status === 'borrowed')
                        <div class="alert {{ $request->end_date < now() ? 'alert-danger' : 'alert-info' }} p-2 mb-0">
                            @if($request->end_date < now())
                                <small><strong>⚠️ QUÁ HẠN</strong><br>{{ formatRemainingTime($request->end_date) }}</small>
                            @else
                                <small><strong>✓ Còn Lại</strong><br>{{ formatRemainingTime($request->end_date) }}</small>
                            @endif
                        </div>
                    @endif

                    @if($request->actual_return_date)
                        <div class="mt-2">
                            <small class="text-muted d-block">✓ Trả Thực Tế</small>
                            <strong class="text-success">{{ $request->actual_return_date->format('d/m/Y') }}</strong>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    @if($request->status === 'renewal_requested')
                        <div class="mt-2">
                            <span class="badge text-bg-info">Yêu cầu gia hạn</span>
                        </div>
                    @elseif($request->status === 'return_requested')
                        <div class="mt-2">
                            <span class="badge text-bg-warning">Yêu cầu trả</span>
                        </div>
                    @elseif($request->status === 'cancelled')
                        <div class="mt-2">
                            <span class="badge text-bg-secondary">Đã hủy</span>
                        </div>
                    @endif
                </div>

                <!-- Right - Actions -->
                <div class="col-md-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('employee.borrowings.show', $request) }}" class="btn btn-primary">
                            <i class="bi bi-eye"></i> Xem Chi Tiết
                        </a>
                        
                        @if($request->status === 'borrowed')
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#renewModal{{ $request->id }}">
                                <i class="bi bi-arrow-repeat"></i> Gia Hạn
                            </button>
                            <form action="{{ route('employee.borrowings.return', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Gửi yêu cầu trả thiết bị đến admin?')">
                                    <i class="bi bi-check-circle"></i> Trả Thiết Bị
                                </button>
                            </form>
                        @elseif($request->status === 'renewal_requested')
                            <button type="button" class="btn btn-info w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt Gia Hạn
                            </button>
                        @elseif($request->status === 'return_requested')
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt Trả
                            </button>
                        @elseif($request->status === 'cancelled')
                            <button type="button" class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-slash-circle"></i> Yêu Cầu Đã Hủy
                            </button>
                        @elseif($request->status === 'pending')
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                            </button>
                        @elseif($request->status === 'rejected')
                            <button type="button" class="btn btn-danger w-100" disabled>
                                <i class="bi bi-x-circle"></i> Từ Chối
                            </button>
                        @elseif($request->status === 'returned')
                            <button type="button" class="btn btn-success w-100" disabled>
                                <i class="bi bi-check-circle"></i> Đã Trả
                            </button>
                        @endif

                        @if($request->status === 'pending' || $request->status === 'borrowed')
                            <form action="{{ route('employee.borrowings.cancel', $request) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Xác nhận hủy yêu cầu này?')">
                                    <i class="bi bi-trash"></i> Hủy
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renew Modal -->
    <div class="modal fade" id="renewModal{{ $request->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Gia Hạn Mượn Thiết Bị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Gia hạn thêm 7 ngày để tiếp tục sử dụng thiết bị</p>
                    <div class="alert alert-light border">
                        <strong>Ngày kết thúc hiện tại:</strong> {{ $request->end_date->format('d/m/Y') }}<br>
                        <strong>Ngày kết thúc mới:</strong> {{ $request->end_date->addDays(7)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="{{ route('employee.borrowings.renew', $request) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-repeat"></i> Xác Nhận Gia Hạn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="alert alert-light border text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted mb-0">Không có yêu cầu mượn nào</p>
        <a href="{{ route('employee.equipment.browse') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-circle"></i> Mượn Thiết Bị
        </a>
    </div>
@endif

<style>
    .borrow-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .borrow-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .equipment-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .equipment-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .equipment-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    .equipment-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem !important;
    }
</style>
@endsection
