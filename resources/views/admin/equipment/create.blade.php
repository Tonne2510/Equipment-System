@extends('layouts.app')

@section('title', 'Thêm Thiết Bị')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Thêm Thiết Bị Mới</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.equipment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh Mục <span class="text-danger">*</span></label>
                        <select name="category_id" id="categorySelect" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn Danh Mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model_name" class="form-control @error('model_name') is-invalid @enderror" 
                               value="{{ old('model_name') }}" placeholder="Nhập tên model (ví dụ: HP Pavilion 14)" required>
                        @error('model_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hãng Sản Xuất</label>
                        <input type="text" name="brand_name" class="form-control @error('brand_name') is-invalid @enderror" 
                               value="{{ old('brand_name') }}" placeholder="Nhập tên hãng (ví dụ: HP, Canon, Sony)">
                        @error('brand_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Serial Number <span class="text-danger">*</span></label>
                        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number') }}" required>
                        @error('serial_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">-- Chọn Trạng Thái --</option>
                            <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Sẵn Sàng</option>
                            <option value="borrowed" {{ old('status') === 'borrowed' ? 'selected' : '' }}>Đang Mượn</option>
                            <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Bảo Trì</option>
                            <option value="damaged" {{ old('status') === 'damaged' ? 'selected' : '' }}>Hỏng</option>
                            <option value="lost" {{ old('status') === 'lost' ? 'selected' : '' }}>Mất</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày Mua</label>
                        <input type="date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date') }}">
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vị Trí Lưu Giữ</label>
                        <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Ví dụ: Phòng 101, Tủ A">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá Mua (VNĐ)</label>
                        <input type="number" name="purchase_cost" class="form-control" value="{{ old('purchase_cost') }}" min="0" step="0.01">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bảo Hành Đến Ngày</label>
                        <input type="date" name="warranty_until" class="form-control" value="{{ old('warranty_until') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi Chú</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình Ảnh</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-plus-circle"></i> Thêm Thiết Bị
                    </button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
