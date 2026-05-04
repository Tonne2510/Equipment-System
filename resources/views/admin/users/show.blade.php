@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3 mb-3">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person" style="font-size: 40px;" class="text-muted"></i>
                    </div>
                @endif
                <div>
                    <h2>👤 Chi Tiết Người Dùng: {{ $user->name }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Sửa
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <!-- Main Info -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">📋 Thông Tin Cơ Bản</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tên:</strong>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Điện Thoại:</strong>
                            <p>{{ $user->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Vai Trò:</strong>
                            <p>
                                <span class="badge bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'manager' ? 'warning' : 'info') }}">
                                    {{ ucfirst($user->role->name) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Trạng Thái:</strong>
                            <p>
                                @if($user->status === 1)
                                    <span class="badge bg-success">✓ Hoạt Động</span>
                                @else
                                    <span class="badge bg-secondary">✕ Bị Vô Hiệu</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">📅 Hoạt Động</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày Tạo:</strong>
                            <p>{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Cập Nhật Lần Cuối:</strong>
                            <p>{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">⚙️ Hành Động</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit"></i> Sửa Thông Tin
                    </a>
                    
                    <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-info w-100" onclick="return confirm('Đặt lại mật khẩu thành &quot;password&quot;?')">
                            <i class="fas fa-key"></i> Đặt Lại Mật Khẩu
                        </button>
                    </form>

                    @if(auth()->id() !== $user->id)
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-{{ $user->status === 1 ? 'danger' : 'success' }} w-100">
                                @if($user->status === 1)
                                    <i class="fas fa-ban"></i> Vô Hiệu Hóa
                                @else
                                    <i class="fas fa-check"></i> Kích Hoạt
                                @endif
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Xóa Người Dùng
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">💡 Ghi Chú</h6>
                    <small>
                        Bạn có thể sửa thông tin cá nhân, đặt lại mật khẩu, hoặc vô hiệu hóa tài khoản từ các nút ở trên.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
