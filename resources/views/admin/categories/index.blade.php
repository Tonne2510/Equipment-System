@extends('layouts.app')

@section('title', 'Quản Lý Danh Mục')

@section('content')
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Quản Lý Danh Mục Thiết Bị</h3>
    </div>
    <div class="col-sm-6 text-end">
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Danh Mục Mới
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất Cả Trạng Thái --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt Động</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Vô Hiệu</option>
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

<!-- Categories Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Danh Mục</h5>
    </div>
    <div class="card-body p-0">
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="categories-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">Icon</th>
                            <th>Tên Danh Mục</th>
                            <th>Slug</th>
                            <th>Mô Tả</th>
                            <th>Thiết Bị</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}" style="font-size: 24px;"></i>
                                    @else
                                        <i class="bi bi-box" style="font-size: 24px;"></i>
                                    @endif
                                </td>
                                <td><strong>{{ $category->name }}</strong></td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <small>{{ Str::limit($category->description, 50) }}</small>
                                </td>
                                <td>
                                    <span class="badge text-bg-primary">{{ $category->items_count }} thiết bị</span>
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge text-bg-success">Hoạt Động</span>
                                    @else
                                        <span class="badge text-bg-secondary">Vô Hiệu</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Xóa" onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}', {{ $category->models_count + $category->items_count }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center text-muted py-4">
                <p class="mb-0">Không tìm thấy danh mục nào</p>
            </div>
        @endif
    </div>
    @if($categories->count() > 0)
        <div class="card-footer d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<script>
function deleteCategory(categoryId, categoryName, itemCount) {
    if (itemCount > 0) {
        alert(`Không thể xóa danh mục "${categoryName}" vì nó đang có ${itemCount} thiết bị liên kết. Vui lòng xóa hoặc di chuyển các thiết bị trước.`);
        return;
    }
    
    if (confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"? Hành động này không thể hoàn tác.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${categoryId}`;
        
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

// Initialize table manager for categories table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('categories-table');
});
</script>
@endsection
