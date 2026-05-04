@extends('layouts.app')

@section('title', 'Thiết Bị')

@section('content')
<!-- Header -->
<div class="mb-5">
    <div class="bg-light rounded-lg p-4 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="mb-1">🏪 Cửa Hàng Thiết Bị</h2>
                <p class="text-muted mb-0">Chọn thiết bị bạn cần mượn</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('employee.borrowings.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-cart"></i> Lịch Sử Mượn
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employee.equipment.browse') }}" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm thiết bị, mã số..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Tất Cả Danh Mục</option>
                    @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất Cả Trạng Thái</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Còn Hàng</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Đang Mượn</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Active Filter Tags -->
@if(request('search') || request('category') || request('status'))
<div class="mb-3">
    @if(request('search'))
    <span class="badge bg-primary me-2">
        Tìm kiếm: {{ request('search') }}
        <a href="{{ route('employee.equipment.browse', array_diff_key(request()->query(), ['search' => ''])) }}" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    @endif
    @if(request('category'))
    <span class="badge bg-info me-2">
        Danh mục: {{ $categories->find(request('category'))->name ?? '' }}
        <a href="{{ route('employee.equipment.browse', array_diff_key(request()->query(), ['category' => ''])) }}" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    @endif
    @if(request('status'))
    <span class="badge bg-warning me-2">
        Trạng thái: {{ request('status') == 'available' ? 'Còn Hàng' : 'Đang Mượn' }}
        <a href="{{ route('employee.equipment.browse', array_diff_key(request()->query(), ['status' => ''])) }}" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    @endif
</div>
@endif

<!-- Results -->
<div class="mb-3">
    <p class="text-muted">
        Hiển thị <strong>{{ $equipment->count() }}</strong> thiết bị
        @if(request('search') || request('category') || request('status'))
            từ kết quả tìm kiếm
        @else
            trong hệ thống
        @endif
    </p>
</div>

<!-- Equipment Grid -->
@if($equipment->count() > 0)
<div class="row g-4 mb-5">
    @foreach($equipment as $item)
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card h-100 border-0 shadow-sm equipment-card">
            <!-- Image Container -->
            <div class="position-relative bg-light" style="height: 180px; overflow: hidden;">
                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model->name }}" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                    @else
                        <i class="bi bi-box2 text-muted" style="font-size: 3rem;"></i>
                    @endif
                </div>
                
                <!-- Status Badge -->
                <span class="badge bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'damaged' ? 'danger' : ($item->status === 'lost' ? 'dark' : 'warning')) }} position-absolute top-0 end-0 m-2">
                    @if($item->status === 'available')
                        <i class="bi bi-check-circle"></i> Còn Hàng
                    @elseif($item->status === 'borrowed')
                        <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                    @elseif($item->status === 'damaged')
                        <i class="bi bi-exclamation-circle"></i> Hỏng
                    @else
                        <i class="bi bi-x-circle"></i> Mất
                    @endif
                </span>
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
                <div class="mb-2">
                    <span class="badge bg-light text-dark small">{{ $item->model->category->name }}</span>
                </div>
                
                <h6 class="card-title fw-bold mb-1">{{ $item->model->name }}</h6>
                
                <p class="small text-muted mb-2">
                    <i class="bi bi-tag"></i> {{ $item->model->brand->name ?? 'Không rõ' }}
                </p>
                
                <p class="small mb-2">
                    <strong>S/N:</strong> <code class="bg-light px-2 py-1">{{ $item->serial_number }}</code>
                </p>

                <!-- Spacer -->
                <div class="mt-auto"></div>

                <!-- Action Buttons -->
                <div class="btn-group w-100 mt-3" role="group">
                    <a href="{{ route('employee.borrowings.create', ['equipment' => $item->id]) }}" 
                       class="btn btn-{{ $item->status === 'available' ? 'primary' : 'secondary' }} btn-sm {{ $item->status !== 'available' ? 'disabled' : '' }}">
                        <i class="bi bi-lg"></i> Mượn
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#equipmentModal{{ $item->id }}" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Detail Modal -->
    <div class="modal fade" id="equipmentModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $item->model->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Danh Mục:</strong> {{ $item->model->category->name }}</p>
                    <p><strong>Hãng:</strong> {{ $item->model->brand->name ?? 'Không rõ' }}</p>
                    <p><strong>Mã Serial:</strong> <code>{{ $item->serial_number }}</code></p>
                    <p><strong>Mã Tài Sản:</strong> <code>{{ $item->asset_tag ?? 'N/A' }}</code></p>
                    <p><strong>Ngày Mua:</strong> {{ $item->purchase_date?->format('d/m/Y') ?? 'N/A' }}</p>
                    <p><strong>Giá Mua:</strong> {{ number_format($item->purchase_cost, 0) }}₫</p>
                    <p><strong>Trạng Thái:</strong> 
                        <span class="badge bg-{{ $item->status === 'available' ? 'success' : 'danger' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </p>
                    @if($item->notes)
                    <p><strong>Ghi Chú:</strong> {{ $item->notes }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    @if($item->status === 'available')
                    <a href="{{ route('employee.borrowings.create', ['equipment' => $item->id]) }}" class="btn btn-primary">
                        <i class="bi bi-lg"></i> Mượn Thiết Bị
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-secondary text-center py-5" role="alert">
    <div class="mb-3">
        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
    </div>
    <h5>Không Tìm Thấy Thiết Bị</h5>
    <p class="mb-0">Không có thiết bị nào phù hợp với tiêu chí tìm kiếm của bạn</p>
</div>
@endif

<style>
.equipment-card {
    transition: all 0.3s ease;
}

.equipment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.equipment-card img {
    transition: transform 0.3s ease;
}

.equipment-card:hover img {
    transform: scale(1.05);
}
</style>
@endsection
