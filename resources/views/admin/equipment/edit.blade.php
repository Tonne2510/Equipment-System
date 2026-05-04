@extends('layouts.app')

@section('title', 'Chỉnh Sửa Thiết Bị')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Chỉnh Sửa Thiết Bị: {{ $equipment->serial_number }}</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.equipment.show', $equipment) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh Mục</label>
                        <select name="category_id" class="form-select" disabled>
                            <option value="{{ $equipment->model->category->id }}">{{ $equipment->model->category->name }}</option>
                        </select>
                        <small class="text-muted">Không thể thay đổi</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Model</label>
                        <select name="model_id" class="form-select" disabled>
                            <option value="{{ $equipment->model->id }}">{{ $equipment->model->name }}</option>
                        </select>
                        <input type="hidden" name="model_id" value="{{ $equipment->model->id }}">
                        <small class="text-muted">Không thể thay đổi</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="available" {{ $equipment->status === 'available' ? 'selected' : '' }}>Sẵn Sàng</option>
                            <option value="borrowed" {{ $equipment->status === 'borrowed' ? 'selected' : '' }}>Đang Mượn</option>
                            <option value="damaged" {{ $equipment->status === 'damaged' ? 'selected' : '' }}>Hỏng</option>
                            <option value="lost" {{ $equipment->status === 'lost' ? 'selected' : '' }}>Mất</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Serial Number</label>
                        <input type="text" name="serial_number" class="form-control" value="{{ $equipment->serial_number }}" disabled>
                        <small class="text-muted">Không thể thay đổi</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày Mua</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ $equipment->purchase_date ? $equipment->purchase_date->format('Y-m-d') : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá Mua (VNĐ)</label>
                        <input type="number" name="purchase_cost" class="form-control" step="0.01" value="{{ $equipment->purchase_cost }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bảo Hành Đến Ngày</label>
                        <input type="date" name="warranty_until" class="form-control" value="{{ $equipment->warranty_until ? $equipment->warranty_until->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi Chú</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $equipment->notes }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Hình Ảnh Hiện Tại</label>
                    @if($equipment->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $equipment->image) }}" alt="Equipment" style="max-width: 150px; border-radius: 5px;">
                        </div>
                    @endif
                    <label class="form-label">Thay Đổi Hình Ảnh (Tùy Chọn)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save"></i> Cập Nhật
                    </button>
                    <a href="{{ route('admin.equipment.show', $equipment) }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
