@extends('layouts.app')

@section('title', 'Sửa Danh Mục')

@section('content')
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Sửa Danh Mục</h3>
    </div>
    <div class="col-sm-6 text-end">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay Lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên Danh Mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" 
                               placeholder="Nhập tên danh mục" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô Tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Nhập mô tả danh mục">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Icon -->
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon (Bootstrap Icon Class)</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="{{ old('icon', $category->icon) ?: 'bi bi-box' }}" id="iconPreview"></i>
                            </span>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                   id="icon" name="icon" value="{{ old('icon', $category->icon) }}" 
                                   placeholder="Ví dụ: bi bi-box, bi bi-laptop, bi bi-keyboard"
                                   onchange="updateIconPreview()">
                        </div>
                        @error('icon')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Xem danh sách icon: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
                        </small>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="">-- Chọn Trạng Thái --</option>
                            <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>Hoạt Động</option>
                            <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>Vô Hiệu</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mb-0">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Lưu Thay Đổi
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Gợi Ý Icon</h6>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-box')">
                        <i class="bi bi-box"></i> Box
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-laptop')">
                        <i class="bi bi-laptop"></i> Laptop
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-keyboard')">
                        <i class="bi bi-keyboard"></i> Keyboard
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-mouse')">
                        <i class="bi bi-mouse"></i> Mouse
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-phone')">
                        <i class="bi bi-phone"></i> Phone
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-camera')">
                        <i class="bi bi-camera"></i> Camera
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-printer')">
                        <i class="bi bi-printer"></i> Printer
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="setIcon('bi bi-headphones')">
                        <i class="bi bi-headphones"></i> Headset
                    </button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">Thông Tin Danh Mục</h6>
                <p class="mb-2">
                    <small class="text-muted">Slug:</small><br>
                    <code>{{ $category->slug }}</code>
                </p>
                <p class="mb-2">
                    <small class="text-muted">Model:</small><br>
                    <strong>{{ $category->models()->count() }}</strong> model
                </p>
                <p class="mb-0">
                    <small class="text-muted">Thiết Bị:</small><br>
                    <strong>{{ $category->items()->count() }}</strong> thiết bị
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function updateIconPreview() {
    const iconInput = document.getElementById('icon').value;
    const preview = document.getElementById('iconPreview');
    if (iconInput) {
        preview.className = iconInput;
    } else {
        preview.className = 'bi bi-box';
    }
}

function setIcon(iconClass) {
    document.getElementById('icon').value = iconClass;
    updateIconPreview();
}
</script>
@endsection
