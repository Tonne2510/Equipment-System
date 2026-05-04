@extends('layouts.app')

@section('content')
<!-- Header Section -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Quản Lý Người Dùng</h3>
    </div>
    <div class="col-sm-6 text-end">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm Người Dùng
        </a>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Tìm Kiếm & Lọc</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm theo tên hoặc email</label>
                <input type="text" name="search" class="form-control" placeholder="Nhập tên hoặc email" value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Vai trò</label>
                <select name="role" class="form-select">
                    <option value="">-- Tất cả vai trò --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tìm Kiếm
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table Card -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Người Dùng <span class="badge text-bg-primary">{{ $users->total() }} người</span></h5>
    </div>
    <div class="card-body p-0">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="users-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai Trò</th>
                            <th>Hình Ảnh</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Tạo</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $user->role->name === 'admin' ? 'danger' : ($user->role->name === 'manager' ? 'warning' : 'info') }}">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" title="{{ $user->name }}">
                                    @else
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status === 1)
                                        <span class="badge text-bg-success">Hoạt Động</span>
                                    @else
                                        <span class="badge text-bg-secondary">Bị Vô Hiệu</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if(auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-{{ $user->status === 1 ? 'danger' : 'success' }}" title="{{ $user->status === 1 ? 'Vô Hiệu' : 'Kích Hoạt' }}">
                                                    <i class="bi bi-{{ $user->status === 1 ? 'lock' : 'unlock' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer bg-light">
                {{ $users->links() }}
            </div>
        @else
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Không có người dùng nào.
                </div>
            </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    // Initialize table manager for users table
    document.addEventListener('DOMContentLoaded', function() {
        new TableManager('users-table');
    });
</script>
@endsection
@endsection
