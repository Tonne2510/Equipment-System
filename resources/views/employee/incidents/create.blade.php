@extends('layouts.app')

@section('title', 'Báo Cáo Sự Cố')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Báo Cáo Sự Cố Thiết Bị</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.incidents.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Danh Sách
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('employee.incidents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Thiết Bị <span class="text-danger">*</span></label>
                    <select name="equipment_item_id" class="form-select @error('equipment_item_id') is-invalid @enderror" required>
                        <option value="">-- Chọn Thiết Bị --</option>
                        @foreach($myEquipment as $item)
                            <option value="{{ $item->id }}" {{ old('equipment_item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->model->name }} ({{ $item->serial_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('equipment_item_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Loại Sự Cố <span class="text-danger">*</span></label>
                        <select name="incident_type" class="form-select @error('incident_type') is-invalid @enderror" required>
                            <option value="">-- Chọn Loại --</option>
                            <option value="damaged" {{ old('incident_type') === 'damaged' ? 'selected' : '' }}>Hư Hại</option>
                            <option value="malfunction" {{ old('incident_type') === 'malfunction' ? 'selected' : '' }}>Hỏng Hóc</option>
                            <option value="lost" {{ old('incident_type') === 'lost' ? 'selected' : '' }}>Mất Tích</option>
                            <option value="theft" {{ old('incident_type') === 'theft' ? 'selected' : '' }}>Bị Trộm</option>
                            <option value="other" {{ old('incident_type') === 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('incident_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mức Độ <span class="text-danger">*</span></label>
                        <select name="severity" class="form-select @error('severity') is-invalid @enderror" required>
                            <option value="">-- Chọn Mức Độ --</option>
                            <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Thấp</option>
                            <option value="medium" {{ old('severity') === 'medium' ? 'selected' : '' }}>Trung Bình</option>
                            <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>Cao</option>
                        </select>
                        @error('severity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mô Tả Sự Cố <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Đính Kèm Hình Ảnh (Tùy Chọn)</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">Có thể chọn nhiều file hình ảnh</small>
                </div>

                <div class="alert alert-info">
                    <strong>Lưu Ý:</strong> Báo cáo sự cố càng chi tiết, quản trị viên sẽ xử lý nhanh chóng hơn. Nếu thiết bị bị hỏng do lỗi của bạn, bạn có thể bị phạt.
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send"></i> Gửi Báo Cáo
                    </button>
                    <a href="{{ route('employee.incidents.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
