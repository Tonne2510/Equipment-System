@extends('layouts.app')

@section('title', 'Tìm Thiết Bị')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Tìm Thiết Bị Để Mượn</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.borrowings.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tạo Yêu Cầu
            </a>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc mã thiết bị..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="category" class="form-select">
                        <option value="">-- Tất Cả Danh Mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm Kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Equipment Grid -->
    <div class="row">
        @forelse($equipment as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="Hình thiết bị" class="img-fluid rounded" style="max-height: 150px;">
                            @else
                                <div class="bg-light rounded p-4">
                                    <i class="fas fa-laptop fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <h6 class="card-title">{{ $item->model->name }}</h6>
                        <p class="text-muted small mb-2">{{ $item->model->brand?->name ?? 'Chưa xác định' }}</p>
                        <p class="mb-2">
                            <strong>Mã:</strong> {{ $item->serial_number }}
                        </p>
                        <p class="mb-3">
                            @if($item->status === 'available')
                                <span class="badge bg-success">✓ Sẵn Sàng</span>
                            @elseif($item->status === 'borrowed')
                                <span class="badge bg-info">⟳ Đang Mượn</span>
                            @elseif($item->status === 'maintenance')
                                <span class="badge bg-warning">⚙ Bảo Trì</span>
                            @elseif($item->status === 'damaged')
                                <span class="badge bg-danger">✗ Hỏng</span>
                            @elseif($item->status === 'lost')
                                <span class="badge bg-dark">⊘ Mất</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                            @endif
                        </p>
                        <a href="{{ route('employee.equipment.show', $item) }}" class="btn btn-primary w-100 btn-sm">
                            Xem Chi Tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Không tìm thấy thiết bị sẵn sàng nào
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12 d-flex justify-content-center">
            {{ $equipment->links() }}
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
