@extends('layouts.app')

@section('title', 'Quản Lý Thiết Bị')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Quản Lý Thiết Bị</h3>
    </div>
    <div class="col-sm-6 text-end">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-tag"></i> Quản Lý Danh Mục
        </a>
        <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Mới
        </a>
    </div>
</div>

<!-- Search & Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">-- Chọn Danh Mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Trạng Thái --</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Sẵn Sàng</option>
                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Đang Mượn</option>
                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Bảo Trì</option>
                    <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>Hỏng</option>
                    <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Mất</option>
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

<!-- Equipment Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Thiết Bị</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="equipment-table">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Danh Mục</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Mua</th>
                        <th class="text-center">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                        <tr>
                            <td>
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $item->serial_number }}</strong></td>
                            <td>{{ $item->model->name }}</td>
                            <td>
                                <span class="badge text-bg-info">{{ $item->model->category->name }}</span>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'damaged' ? 'danger' : ($item->status === 'lost' ? 'dark' : 'warning')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td>{{ $item->purchase_date ? $item->purchase_date->format('d/m/Y') : '-' }}</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-outline-info" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.equipment.edit', $item) }}" class="btn btn-outline-warning" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Xóa" onclick="deleteEquipment({{ $item->id }}, '{{ $item->serial_number }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Không tìm thấy thiết bị nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-center">
        {{ $equipment->links() }}
    </div>
</div>

<script>
function deleteEquipment(equipmentId, serialNumber) {
    if (confirm(`Bạn có chắc chắn muốn xóa thiết bị ${serialNumber}? Hành động này không thể hoàn tác.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/equipment/${equipmentId}`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const tokenInput = document.createElement('input');
            tokenInput.type = 'hidden';
            tokenInput.name = '_token';
            tokenInput.value = csrfToken.content;
            form.appendChild(tokenInput);
        }
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize table manager for equipment table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('equipment-table');
});
</script>
@endsection
