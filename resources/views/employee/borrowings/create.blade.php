@extends('layouts.app')

@section('title', 'Tạo Yêu Cầu Mượn')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Form Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    <i class="bi bi-bag-check"></i> Tạo Yêu Cầu Mượn Thiết Bị
                </h4>

                <form action="{{ route('employee.borrowings.store') }}" method="POST">
                    @csrf

                    <!-- Equipment Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">Chọn Thiết Bị</label>
                        <div id="equipmentItems" class="border rounded p-3 bg-light">
                            @if($selectedEquipment ?? false)
                                <div class="equipment-item mb-3 p-3 bg-white rounded border d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $selectedEquipment->model->name }}</h6>
                                        <small class="text-muted">S/N: {{ $selectedEquipment->serial_number }}</small>
                                    </div>
                                    <input type="hidden" name="equipment_ids[]" value="{{ $selectedEquipment->id }}">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            @else
                                <p class="text-muted mb-0">
                                    <i class="bi bi-info-circle"></i> 
                                    <a href="{{ route('employee.equipment.browse') }}" class="link-primary">Chọn thiết bị từ cửa hàng</a>
                                </p>
                            @endif
                        </div>
                        <div class="form-text">Chọn từ 1 đến 5 thiết bị để mượn</div>
                        @error('equipment_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Borrow Period -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label fw-bold">Ngày Bắt Đầu Mượn</label>
                            <input type="date" id="startDate" name="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                   value="{{ old('start_date', now()->format('Y-m-d')) }}" 
                                   min="{{ now()->format('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label fw-bold">Ngày Trả Dự Kiến (Tối đa 7 ngày)</label>
                            <input type="date" id="endDate" name="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                   value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" 
                                   min="{{ now()->format('Y-m-d') }}"
                                   max="{{ now()->addDays(7)->format('Y-m-d') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Duration Display -->
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="bi bi-calendar-event"></i>
                        <strong id="durationDisplay">Thời hạn mượn:</strong> <span id="dayCount">7</span> ngày
                    </div>

                    <!-- Reason for Borrowing -->
                    <div class="mb-4">
                        <label for="reason" class="form-label fw-bold">Lý Do Mượn</label>
                        <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror" 
                                  rows="4" placeholder="Mô tả lý do bạn cần mượn thiết bị này..."
                                  required>{{ old('reason') }}</textarea>
                        <div class="form-text">Ví dụ: Dự án Q2, Training, Sự kiện, v.v...</div>
                        @error('reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Tôi đã đọc và đồng ý với <a href="#" class="link-primary">Điều khoản sử dụng thiết bị</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                            <i class="bi bi-check-lg"></i> Gửi Yêu Cầu Mượn
                        </button>
                        <a href="{{ route('employee.equipment.browse') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Tiếp Tục Chọn
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar - Order Summary -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-receipt"></i> Tóm Tắt Yêu Cầu
                </h5>

                <!-- Equipment Summary -->
                <div class="mb-4 pb-4 border-bottom">
                    <h6 class="mb-3">Thiết Bị Chọn</h6>
                    @if($selectedEquipment ?? false)
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-0 fw-bold">{{ $selectedEquipment->model->name }}</p>
                                <small class="text-muted">{{ $selectedEquipment->model->category->name }}</small>
                            </div>
                            <span class="badge bg-primary">1</span>
                        </div>
                    @else
                        <p class="text-muted small mb-0">Chưa chọn thiết bị</p>
                    @endif
                </div>

                <!-- Duration Summary -->
                <div class="mb-4 pb-4 border-bottom">
                    <h6 class="mb-3">Thời Hạn Mượn</h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Từ</small>
                            <p class="mb-0 fw-bold" id="displayStartDate">-</p>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Đến</small>
                            <p class="mb-0 fw-bold" id="displayEndDate">-</p>
                        </div>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="alert alert-warning mb-4" role="alert">
                    <h6 class="alert-heading mb-2">
                        <i class="bi bi-exclamation-triangle"></i> Lưu Ý Quan Trọng
                    </h6>
                    <ul class="small mb-0">
                        <li>Mượn tối đa 7 ngày</li>
                        <li>Có thể gia hạn 1 lần</li>
                        <li>Chịu trách nhiệm về mất mát/hỏng hóc</li>
                        <li>Trả đúng hạn để tránh phạt</li>
                    </ul>
                </div>

                <!-- Status Badge -->
                <div class="alert alert-info" role="alert">
                    <small>
                        <strong>Trạng Thái:</strong> Yêu cầu sẽ chờ duyệt từ quản lý
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update duration display
function updateDuration() {
    const startDate = new Date(document.getElementById('startDate').value);
    const endDate = new Date(document.getElementById('endDate').value);
    
    if (startDate && endDate) {
        const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
        document.getElementById('dayCount').textContent = Math.max(1, days);
        
        // Format display dates
        const formatDate = (date) => {
            return date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric' });
        };
        
        document.getElementById('displayStartDate').textContent = formatDate(startDate);
        document.getElementById('displayEndDate').textContent = formatDate(endDate);
    }
}

document.getElementById('startDate').addEventListener('change', updateDuration);
document.getElementById('endDate').addEventListener('change', updateDuration);

// Initialize on load
window.addEventListener('load', updateDuration);

// Set minimum and maximum end date (7 days from start)
document.getElementById('startDate').addEventListener('change', function() {
    const startDate = new Date(this.value);
    const minEndDate = new Date(startDate);
    minEndDate.setDate(minEndDate.getDate() + 1);
    
    const maxEndDate = new Date(startDate);
    maxEndDate.setDate(maxEndDate.getDate() + 7);
    
    const endDateInput = document.getElementById('endDate');
    endDateInput.min = minEndDate.toISOString().split('T')[0];
    endDateInput.max = maxEndDate.toISOString().split('T')[0];
    
    // Reset end date if it's outside the new range
    const currentEndDate = new Date(endDateInput.value);
    if (currentEndDate < minEndDate || currentEndDate > maxEndDate) {
        endDateInput.value = minEndDate.toISOString().split('T')[0];
        updateDuration();
    }
});
</script>

<style>
.sticky-top {
    top: 100px !important;
}
</style>
@endsection
