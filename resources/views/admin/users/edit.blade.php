@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>📝 Sửa Thông Tin Người Dùng</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Điện Thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Avatar -->
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Hình Ảnh Avatar</label>
                            <div class="mb-2">
                                @if($user->avatar)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                   id="avatar" name="avatar" accept="image/*">
                            <small class="form-text text-muted">Định dạng: JPG, PNG, GIF (Max 2MB). Để trống nếu không muốn thay đổi</small>
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Vai Trò <span class="text-danger">*</span></label>
                            <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu Thay Đổi
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">👤 Thông Tin Tài Khoản</h5>
                    <p class="mb-2">
                        <strong>ID:</strong> {{ $user->id }}
                    </p>
                    <p class="mb-2">
                        <strong>Ngày Tạo:</strong> {{ $user->created_at->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-2">
                        <strong>Cập Nhật:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}
                    </p>
                    <hr>
                    <h6 class="mt-3">🔐 Quản Lý Bảo Mật</h6>
                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning w-100" onclick="return confirm('Đặt lại mật khẩu thành &quot;password&quot;?')">
                            <i class="fas fa-key"></i> Đặt Lại Mật Khẩu
                        </button>
                    </form>
                    
                    @if(auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-{{ $user->status === 1 ? 'danger' : 'success' }} w-100">
                                @if($user->status === 1)
                                    <i class="fas fa-ban"></i> Vô Hiệu Hóa
                                @else
                                    <i class="fas fa-check"></i> Kích Hoạt
                                @endif
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
