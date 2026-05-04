@extends('layouts.app')

@section('content')
<style>
    .nav-tabs .nav-link {
        transition: all 0.3s ease;
        background-color: #e9ecef !important;
        color: #495057 !important;
        border: none !important;
        border-radius: 5px 5px 0 0 !important;
        padding: 12px 20px !important;
        margin-right: 5px !important;
        font-weight: 600 !important;
    }
    .nav-tabs .nav-link.active {
        background-color: #0d6efd !important;
        color: white !important;
    }
</style>
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Hồ Sơ Cá Nhân</h3>
    </div>
</div>

<!-- Tabs -->
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active fw-bold" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">
                    <i class="bi bi-person-circle" style="font-size: 1.2rem;"></i> Thông Tin Cá Nhân
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-bold" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">
                    <i class="bi bi-lock-fill" style="font-size: 1.2rem;"></i> Đổi Mật Khẩu
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Profile Tab -->
            <div class="tab-pane fade show active" id="profile" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); color: white;">
                                <h5 class="card-title mb-0"><i class="bi bi-person-circle"></i> Thông Tin Cá Nhân</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.update') }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Điện Thoại</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="bi bi-check-circle"></i> Lưu Thay Đổi
                                        </button>
                                        <a href="{{ auth()->user()->role->name === 'employee' ? route('employee.dashboard') : route('admin.dashboard') }}" class="btn btn-outline-dark btn-lg">
                                            <i class="bi bi-x-circle"></i> Hủy
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Info Sidebar -->
                    <div class="col-lg-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Thông Tin Tài Khoản</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">
                                    <strong>Vai Trò:</strong>
                                    <br>
                                    <span class="badge text-bg-{{ auth()->user()->role->name === 'admin' ? 'danger' : (auth()->user()->role->name === 'manager' ? 'warning' : 'info') }}">
                                        {{ ucfirst(auth()->user()->role->name) }}
                                    </span>
                                </p>
                                <p class="mb-3">
                                    <strong>Ngày Tạo:</strong>
                                    <br>{{ auth()->user()->created_at->format('d/m/Y') }}
                                </p>
                                <p class="mb-0">
                                    <strong>Cập Nhật Lần Cuối:</strong>
                                    <br>{{ auth()->user()->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Password Tab -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white;">
                                <h5 class="card-title mb-0"><i class="bi bi-lock-fill"></i> Đổi Mật Khẩu</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.update-password') }}">
                                    @csrf
                                    @method('PUT')

                                    <!-- Current Password -->
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Mật Khẩu Hiện Tại <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" name="current_password" required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- New Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật Khẩu Mới <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Tối thiểu 8 ký tự, khác với mật khẩu hiện tại</small>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Xác Nhận Mật Khẩu Mới <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                               id="password_confirmation" name="password_confirmation" required>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Buttons -->
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-danger btn-lg">
                                            <i class="bi bi-key"></i> Thay Đổi Mật Khẩu
                                        </button>
                                        <a href="{{ auth()->user()->role->name === 'employee' ? route('employee.dashboard') : route('admin.dashboard') }}" class="btn btn-outline-dark btn-lg">
                                            <i class="bi bi-x-circle"></i> Hủy
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Security Info -->
                    <div class="col-lg-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0 text-dark">
                                    <i class="bi bi-shield-lock"></i> Bảo Mật
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="small mb-2">Sử dụng một mật khẩu mạnh với:</p>
                                <ul class="small mb-0">
                                    <li>Ít nhất 8 ký tự</li>
                                    <li>Chữ hoa, chữ thường</li>
                                    <li>Số và ký tự đặc biệt</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show password tab if there are password errors
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
            document.getElementById('password-tab').click();
        @endif
    });
</script>
@endsection
