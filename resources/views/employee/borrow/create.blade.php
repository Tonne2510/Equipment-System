@extends('layouts.app')

@section('title', 'Tạo Yêu Cầu Mượn')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Tạo Yêu Cầu Mượn Thiết Bị</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('employee.borrowings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('employee.borrowings.store') }}" method="POST" id="borrowForm">
                @csrf

                <!-- Date Range -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày Trả <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="endDate" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                        <small class="text-muted">Tối đa 7 ngày kể từ ngày bắt đầu</small>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Equipment Selection -->
                <div class="mb-4">
                    <label class="form-label">Chọn Thiết Bị <span class="text-danger">*</span></label>
                    <div class="row" id="equipmentList">
                        @foreach($availableEquipment as $item)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="equipment_ids[]" value="{{ $item->id }}" id="eq{{ $item->id }}">
                                            <label class="form-check-label" for="eq{{ $item->id }}">
                                                <strong>{{ $item->model->name }}</strong><br>
                                                <small class="text-muted">
                                                    Mã: {{ $item->serial_number }}<br>
                                                    Thương hiệu: {{ $item->model->brand?->name ?? 'Chưa xác định' }}
                                                </small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('equipment_ids')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <label class="form-label">Lý Do Mượn <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Additional Notes -->
                <div class="mb-4">
                    <label class="form-label">Ghi Chú Thêm</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>

                <!-- Summary -->
                <div class="alert alert-info">
                    <strong>Lưu Ý Quan Trọng:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Thời gian mượn mặc định là 7 ngày</li>
                        <li>Bạn có thể gia hạn lại 7 ngày nếu chưa trả</li>
                        <li>Trả quá hạn sẽ bị phạt 50,000đ/ngày</li>
                        <li>Báo cáo ngay nếu thiết bị bị hỏng hoặc mất</li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-paper-plane"></i> Gửi Yêu Cầu
                    </button>
                    <a href="{{ route('employee.borrowings.index') }}" class="btn btn-secondary">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="equipment_ids[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateSummary);
});

const startDateInput = document.querySelector('input[name="start_date"]');
const endDateInput = document.querySelector('input[name="end_date"]');

endDateInput.addEventListener('change', updateSummary);

// Set minimum and maximum end date (7 days from start)
startDateInput.addEventListener('change', function() {
    const startDate = new Date(this.value);
    const minEndDate = new Date(startDate);
    minEndDate.setDate(minEndDate.getDate() + 1);
    
    const maxEndDate = new Date(startDate);
    maxEndDate.setDate(maxEndDate.getDate() + 7);
    
    endDateInput.min = minEndDate.toISOString().split('T')[0];
    endDateInput.max = maxEndDate.toISOString().split('T')[0];
    
    // Reset end date if it's outside the new range
    const currentEndDate = new Date(endDateInput.value);
    if (currentEndDate < minEndDate || currentEndDate > maxEndDate) {
        endDateInput.value = minEndDate.toISOString().split('T')[0];
        updateSummary();
    }
});

function updateSummary() {
    const selected = document.querySelectorAll('input[name="equipment_ids[]"]:checked').length;
    const endDate = new Date(endDateInput.value);
    const startDate = new Date(startDateInput.value);
    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    
    console.log(`Selected: ${selected} items, Duration: ${days} days`);
}
</script>
@endsection
