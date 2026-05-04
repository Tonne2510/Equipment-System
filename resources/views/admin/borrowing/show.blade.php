@extends('layouts.app')

@section('title', 'Chi Tiết Yêu Cầu Mượn')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div>
                <h1 class="h3 mb-1">Yêu Cầu Mượn #{{ str_pad($borrow->id, 5, '0', STR_PAD_LEFT) }}</h1>
                <div class="badge text-white p-2 {{ 
                    $borrow->status === 'borrowed' ? 'bg-primary' :
                    ($borrow->status === 'pending' ? 'bg-warning' :
                    ($borrow->status === 'approved' ? 'bg-info' :
                    ($borrow->status === 'returned' ? 'bg-success' :
                    ($borrow->status === 'rejected' ? 'bg-danger' :
                    ($borrow->status === 'cancelled' ? 'bg-secondary' : 'bg-dark')))))
                }}">
                    @if($borrow->status === 'borrowed')
                        <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                    @elseif($borrow->status === 'pending')
                        <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                    @elseif($borrow->status === 'approved')
                        <i class="bi bi-check2-circle"></i> Đã Duyệt
                    @elseif($borrow->status === 'returned')
                        <i class="bi bi-check-circle"></i> Đã Trả
                    @elseif($borrow->status === 'rejected')
                        <i class="bi bi-x-circle"></i> Từ Chối
                    @elseif($borrow->status === 'cancelled')
                        <i class="bi bi-slash-circle"></i> Đã Hủy
                    @else
                        {{ ucfirst($borrow->status) }}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.borrowing.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    @if(!$borrow->user)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger border-0" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-3 mt-1" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="alert-heading mb-1"><i class="bi bi-exclamation-triangle-fill"></i> ⚠️ Dữ Liệu Bị Hỏng - Người Dùng Không Tồn Tại</h5>
                        <p class="mb-2">Người dùng của yêu cầu này (ID: <code>{{ $borrow->user_id }}</code>) đã bị xóa khỏi hệ thống nhưng yêu cầu mượn vẫn còn trong database.</p>
                        <hr>
                        <p class="mb-0"><strong>💡 Giải Pháp:</strong></p>
                        <ul class="mb-0 mt-2">
                            <li>Admin vẫn có thể <strong>duyệt/từ chối</strong> yêu cầu này (xem phần "Phê Duyệt Yêu Cầu" bên phải)</li>
                            <li>Admin có thể <strong>xóa yêu cầu</strong> nếu trạng thái là "Chờ Duyệt" hoặc "Từ Chối"</li>
                            <li>Nếu cần khôi phục thông tin người dùng, vui lòng liên hệ hỗ trợ hoặc kiểm tra backup</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row mb-4">
        <!-- Left Column: Request & Equipment Details -->
        <div class="col-md-8">
            <!-- Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📋 Thông Tin Yêu Cầu</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">🕐 Ngày Tạo</small>
                                <strong>{{ $borrow->created_at ? $borrow->created_at->format('d/m/Y H:i:s') : 'N/A' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">✏️ Lần Cập Nhật</small>
                                <strong>{{ $borrow->updated_at ? $borrow->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">👤 Người Yêu Cầu</small>
                                <div class="d-flex align-items-center gap-2">
                                    @if($borrow->user && $borrow->user->avatar)
                                        <img src="{{ asset('storage/' . $borrow->user->avatar) }}" alt="{{ $borrow->user->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $borrow->user ? $borrow->user->name : '⚠️ Không có' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">🏷️ ID Yêu Cầu</small>
                                <strong class="text-primary">#{{ str_pad($borrow->id, 5, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">📊 Trạng Thái</small>
                                <strong>
                                    @if($borrow->status === 'borrowed')
                                        <span class="badge bg-primary px-3 py-2"><i class="bi bi-hand-thumbs-up"></i> Đang Mượn</span>
                                    @elseif($borrow->status === 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split"></i> Chờ Duyệt</span>
                                    @elseif($borrow->status === 'approved')
                                        <span class="badge bg-info px-3 py-2"><i class="bi bi-check2-circle"></i> Đã Duyệt</span>
                                    @elseif($borrow->status === 'returned')
                                        <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle"></i> Đã Trả</span>
                                    @elseif($borrow->status === 'rejected')
                                        <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle"></i> Từ Chối</span>
                                    @elseif($borrow->status === 'cancelled')
                                        <span class="badge bg-secondary px-3 py-2"><i class="bi bi-slash-circle"></i> Đã Hủy</span>
                                    @endif
                                </strong>
                            </div>
                            @if($borrow->approvedBy)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">✅ Duyệt Bởi</small>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($borrow->approvedBy->avatar)
                                            <img src="{{ asset('storage/' . $borrow->approvedBy->avatar) }}" alt="{{ $borrow->approvedBy->name }}" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        @endif
                                        <strong>{{ $borrow->approvedBy->name }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lý Do Mượn -->
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2">💬 Lý Do Mượn</small>
                        @if($borrow->reason)
                            <p class="mb-0 bg-light p-3 rounded">{{ $borrow->reason }}</p>
                        @else
                            <p class="mb-0 bg-light p-3 rounded text-muted"><em>Không có ghi chú</em></p>
                        @endif
                    </div>

                    <!-- Lý Do Từ Chối (if rejected) -->
                    @if($borrow->status === 'rejected' && $borrow->rejection_reason)
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><i class="bi bi-exclamation-circle-fill text-danger"></i> Lý Do Từ Chối</small>
                        <p class="mb-0 bg-danger bg-opacity-10 p-3 rounded text-danger">{{ $borrow->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Equipment Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📦 Thiết Bị Mượn ({{ $borrow->items->count() }} cái)</h5>
                </div>
                <div class="card-body p-0">
                    @if($borrow->items->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Ảnh</th>
                                    <th>Model / ID</th>
                                    <th>Serial Number</th>
                                    <th>Thương Hiệu</th>
                                    <th>Danh Mục</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrow->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->model ? $item->model->name : 'Thiết bị' }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" title="{{ $item->model ? $item->model->name : 'Thiết bị' }}">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 4px;">
                                                    <i class="bi bi-box2 text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $item->model ? $item->model->name : '⚠️ N/A' }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $item->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded">{{ $item->serial_number }}</code>
                                        </td>
                                        <td>{{ $item->model && $item->model->brand ? $item->model->brand->name : '❓ N/A' }}</td>
                                        <td>{{ $item->model && $item->model->category ? $item->model->category->name : '❓ N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $item->status === 'borrowed' ? 'info' : ($item->status === 'available' ? 'success' : ($item->status === 'maintenance' ? 'warning' : ($item->status === 'damaged' ? 'danger' : ($item->status === 'lost' ? 'danger' : 'secondary')))) }} px-2 py-2">
                                                @if($item->status === 'available')
                                                    <i class="bi bi-check-circle"></i> Sẵn Sàng
                                                @elseif($item->status === 'borrowed')
                                                    <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                                                @elseif($item->status === 'maintenance')
                                                    <i class="bi bi-tools"></i> Bảo Trì
                                                @elseif($item->status === 'damaged')
                                                    <i class="bi bi-exclamation-triangle"></i> Hỏng
                                                @elseif($item->status === 'lost')
                                                    <i class="bi bi-question-circle"></i> Mất
                                                @else
                                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Không có thiết bị trong yêu cầu này</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Borrower Info & Actions -->
        <div class="col-md-4">
            <!-- Borrower Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">👤 Thông Tin Người Mượn</h5>
                </div>
                <div class="card-body">
                    @if($borrow->user)
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                @if($borrow->user->avatar)
                                    <img src="{{ asset('storage/' . $borrow->user->avatar) }}" alt="{{ $borrow->user->name }}" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $borrow->user->name }}</h6>
                                    <small class="text-muted">ID: {{ $borrow->user->id }}</small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Email</small>
                            <strong><a href="mailto:{{ $borrow->user->email }}">{{ $borrow->user->email }}</a></strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Điện Thoại</small>
                            <strong>{{ $borrow->user->phone ?? '📞 Chưa cập nhật' }}</strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Vai Trò</small>
                            @if($borrow->user->role)
                                <span class="badge bg-{{ $borrow->user->role->name === 'admin' ? 'danger' : ($borrow->user->role->name === 'manager' ? 'warning' : 'info') }} px-3 py-2">
                                    @if($borrow->user->role->name === 'admin')
                                        <i class="bi bi-shield-lock"></i> Admin
                                    @elseif($borrow->user->role->name === 'manager')
                                        <i class="bi bi-person-badge"></i> Quản Lý
                                    @else
                                        <i class="bi bi-person-check"></i> Nhân Viên
                                    @endif
                                </span>
                            @else
                                <span class="badge bg-secondary">❓ Chưa phân công</span>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning mb-3 border-0">
                            <div class="d-flex align-items-start gap-2">
                                <i class="bi bi-info-circle-fill" style="font-size: 1.2rem; margin-top: 2px;"></i>
                                <div>
                                    <strong>Dữ Liệu Mồ Côi</strong>
                                    <p class="small mb-1">Yêu cầu mượn này không có người dùng liên kết (user_id: NULL)</p>
                                    <p class="small mb-0 text-muted">Người dùng có thể đã bị xóa khỏi hệ thống</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            @if(in_array($borrow->status, ['pending', 'rejected']))
                                <form method="POST" action="{{ route('admin.borrowing.destroy', $borrow) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Xóa yêu cầu mượn này? Hành động không thể hoàn tác.');">
                                        <i class="bi bi-trash"></i> Xóa Yêu Cầu
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="bi bi-lock"></i> Không Thể Xóa ({{ ucfirst($borrow->status) }})
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📅 Thời Hạn Mượn</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">📌 Từ Ngày</small>
                        <strong class="text-primary fs-6">
                            @if($borrow->start_date)
                                {{ $borrow->start_date->format('d/m/Y') }} 
                                <span class="text-muted small">({{ $borrow->start_date->format('l') }})</span>
                            @else
                                N/A
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">📌 Đến Ngày</small>
                        <strong class="text-primary fs-6">
                            @if($borrow->end_date)
                                {{ $borrow->end_date->format('d/m/Y') }}
                                <span class="text-muted small">({{ $borrow->end_date->format('l') }})</span>
                            @else
                                N/A
                            @endif
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">⏱️ Thời Hạn Mượn</small>
                        <strong>
                            @if($borrow->start_date && $borrow->end_date)
                                {{ $borrow->end_date->diffInDays($borrow->start_date) + 1 }} ngày
                            @else
                                N/A
                            @endif
                        </strong>
                    </div>

                    @if($borrow->status === 'borrowed')
                        <div class="alert alert-{{ $borrow->end_date && $borrow->end_date->isPast() ? 'danger' : 'info' }} p-3 mb-0 border-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-uppercase" style="font-size: 0.9rem;">
                                    @if($borrow->end_date && $borrow->end_date->isPast())
                                        <i class="bi bi-exclamation-triangle-fill"></i> QUÁ HẠN
                                    @else
                                        <i class="bi bi-check-circle-fill"></i> CÒN LẠI
                                    @endif
                                </strong>
                                <span class="fs-5">
                                    <strong>
                                        @if($borrow->end_date && $borrow->end_date->isPast())
                                            <span class="text-danger">{{ abs($borrow->end_date->diffInDays(now())) }} ngày</span>
                                        @else
                                            <span class="text-success">{{ $borrow->end_date ? $borrow->end_date->diffInDays(now()) : 0 }} ngày</span>
                                        @endif
                                    </strong>
                                </span>
                            </div>
                            <small>
                                @if($borrow->end_date && $borrow->end_date->isPast())
                                    <i class="bi bi-exclamation-circle"></i> Thiết bị cần được trả ngay lập tức
                                @else
                                    <i class="bi bi-info-circle"></i> Còn {{ $borrow->end_date ? $borrow->end_date->diffInDays(now()) : 0 }} ngày để trả
                                @endif
                            </small>
                        </div>
                    @elseif($borrow->actual_return_date)
                        <div class="alert alert-success p-3 mb-0 border-0">
                            <small class="text-muted d-block mb-1"><i class="bi bi-check-circle-fill"></i> Ngày Trả Thực Tế</small>
                            <strong class="text-success fs-6">
                                {{ $borrow->actual_return_date ? $borrow->actual_return_date->format('d/m/Y') : 'N/A' }}
                                <span class="text-muted small">({{ $borrow->actual_return_date ? $borrow->actual_return_date->format('l') : '' }})</span>
                            </strong>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Action Panel -->
            @if(auth()->user()->isAdmin())
                @if(!$borrow->user)
                    <div class="card border-0 shadow-sm mb-4 border-2 border-warning">
                        <div class="card-header bg-warning text-dark border-bottom">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> ⚠️ Dữ Liệu Hỏng - Vẫn Có Thể Xử Lý</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-3 border-0">
                                <p class="small mb-0"><i class="bi bi-info-circle"></i> Người dùng đã bị xóa nhưng yêu cầu vẫn có thể được xử lý thông bình thường.</p>
                                <p class="small mb-0 mt-2 text-muted">Người yêu cầu: <strong>Không có (user_id: {{ $borrow->user_id ?? 'NULL' }})</strong></p>
                            </div>
                        </div>
                    </div>
                @endif
                @if($borrow->status === 'pending')
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark border-bottom">
                            <h5 class="mb-0"><i class="bi bi-hourglass-split"></i> Phê Duyệt Yêu Cầu</h5>
                        </div>
                        <div class="card-body">
                            @if(!$borrow->user)
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Hãy cân nhắc trước khi duyệt!
                                </div>
                            @endif
                            <p class="text-muted small mb-3">Xác nhận duyệt hay từ chối yêu cầu mượn này.</p>
                            <form method="POST" class="d-grid gap-2">
                                @csrf
                                <button type="submit" formaction="{{ route('admin.borrowing.approve', $borrow) }}" class="btn btn-success" onclick="return confirm('Phê duyệt yêu cầu mượn này?{{ $borrow->user ? '' : ' (Cảnh báo: Người dùng không tồn tại)' }}');">
                                    <i class="bi bi-check-circle"></i> Phê Duyệt
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-circle"></i> Từ Chối
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($borrow->status === 'approved')
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white border-bottom">
                            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Ghi Nhận Giao</h5>
                        </div>
                        <div class="card-body">
                            @if(!$borrow->user)
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Vẫn có thể ghi nhận giao.
                                </div>
                            @endif
                            <p class="text-muted small mb-3">Xác nhận đã giao thiết bị cho nhân viên.</p>
                            <form method="POST" action="{{ route('admin.borrowing.mark-borrowed', $borrow) }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Xác nhận giao thiết bị?');">
                                    <i class="bi bi-arrow-right-circle"></i> Xác Nhận Đã Giao
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($borrow->status === 'borrowed')
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-success text-white border-bottom">
                            <h5 class="mb-0"><i class="bi bi-arrow-return-left"></i> Ghi Nhận Trả</h5>
                        </div>
                        <div class="card-body">
                            @if(!$borrow->user)
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Vẫn có thể ghi nhận trả.
                                </div>
                            @endif
                            <p class="text-muted small mb-3">Ghi nhận trả thiết bị từ nhân viên.</p>
                            <form method="POST" action="{{ route('admin.borrowing.mark-returned', $borrow) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="condition" class="form-label small">Tình Trạng Thiết Bị</label>
                                    <select name="condition" id="condition" class="form-select @error('condition') is-invalid @enderror" required>
                                        <option value="">-- Chọn --</option>
                                        <option value="good">Tốt</option>
                                        <option value="damaged">Hư Hỏng</option>
                                        <option value="lost">Mất Thiết Bị</option>
                                    </select>
                                    @error('condition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="damage_notes" class="form-label small">Ghi Chú</label>
                                    <textarea name="damage_notes" id="damage_notes" class="form-control" rows="3" placeholder="Ghi chú tình trạng hư hỏng (nếu có)..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Xác nhận ghi nhận trả thiết bị?');">
                                    <i class="bi bi-check-circle"></i> Xác Nhận Trả
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @else
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white border-bottom">
                        <h5 class="mb-0"><i class="bi bi-lock"></i> Thao Tác Không Sẵn Dùng</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0 border-0">
                            <p class="small mb-0"><i class="bi bi-info-circle"></i> <strong>Admin không phải là người dùng</strong> (hoặc vấn đề khác)</p>
                            <p class="small mb-0 mt-2 text-muted">Chỉ admin hoặc manager mới có thể thực hiện các hành động này.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Renewal Requests -->
            @php
                $pendingRenewals = $borrow->renewalRequests()->where('status', 'pending')->get();
            @endphp
            @if($pendingRenewals->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark border-bottom">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Yêu Cầu Gia Hạn Đang Chờ</h5>
                    </div>
                    <div class="card-body">
                        @foreach($pendingRenewals as $renewal)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <p class="small text-muted mb-1">Hạn Hiện Tại: <strong>{{ $renewal->borrowRequest->end_date->format('d/m/Y') }}</strong></p>
                                <p class="small text-muted mb-2">Hạn Mới Yêu Cầu: <strong class="text-primary">{{ $renewal->new_end_date->format('d/m/Y') }}</strong></p>
                                <div class="d-grid gap-2">
                                    <form method="POST" action="{{ route('admin.borrowing.renewals.approve', $renewal) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Phê duyệt gia hạn?');">
                                            <i class="bi bi-check-circle"></i> Phê Duyệt
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRenewalModal{{ $renewal->id }}">
                                        <i class="bi bi-x-circle"></i> Từ Chối
                                    </button>
                                </div>
                            </div>

                            <!-- Reject Renewal Modal -->
                            <div class="modal fade" id="rejectRenewalModal{{ $renewal->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Từ Chối Gia Hạn</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.borrowing.renewals.reject', $renewal) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Lý Do Từ Chối</label>
                                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-danger">Từ Chối</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Pending Return Requests -->
            @php
                $pendingReturns = $borrow->returnRequests()->where('status', 'pending')->get();
            @endphp
            @if($pendingReturns->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark border-bottom">
                        <h5 class="mb-0"><i class="bi bi-reply-fill"></i> Yêu Cầu Trả Đang Chờ</h5>
                    </div>
                    <div class="card-body">
                        @foreach($pendingReturns as $return)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <p class="small text-muted mb-1">Lý Do: <strong>{{ $return->reason ?? 'Không có' }}</strong></p>
                                <p class="small text-muted mb-2">Ghi Chú: <strong>{{ $return->notes ?? '-' }}</strong></p>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveReturnModal{{ $return->id }}">
                                        <i class="bi bi-check-circle"></i> Phê Duyệt
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectReturnModal{{ $return->id }}">
                                        <i class="bi bi-x-circle"></i> Từ Chối
                                    </button>
                                </div>
                            </div>

                            <!-- Approve Return Modal -->
                            <div class="modal fade" id="approveReturnModal{{ $return->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Phê Duyệt Trả Thiết Bị</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.borrowing.returns.approve', $return) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Tình Trạng Thiết Bị</label>
                                                    <select name="condition" class="form-select" required>
                                                        <option value="">-- Chọn --</option>
                                                        <option value="good">Tốt</option>
                                                        <option value="damaged">Hư Hỏng</option>
                                                        <option value="lost">Mất Thiết Bị</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Ghi Chú</label>
                                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-success">Phê Duyệt</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Return Modal -->
                            <div class="modal fade" id="rejectReturnModal{{ $return->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Từ Chối Trả Thiết Bị</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('admin.borrowing.returns.reject', $return) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Lý Do Từ Chối</label>
                                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-danger">Từ Chối</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- History -->
            @if($borrow->history->count() > 0)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">📜 Lịch Sử</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($borrow->history as $history)
                                <div class="timeline-item mb-3">
                                    <div class="d-flex gap-3">
                                        <div style="width: 30px; text-align: center; flex-shrink: 0;">
                                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                <i class="bi bi-check"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <strong>{{ $history->action }}</strong>
                                                <span class="badge bg-secondary">{{ $history->action_at?->diffForHumans() }}</span>
                                            </div>
                                            <small class="text-muted d-block">{{ $history->action_at?->format('d/m/Y H:i') }}</small>
                                            @if($history->notes)
                                                <small class="text-muted d-block mt-1">{{ $history->notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ Chối Yêu Cầu Mượn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/borrowing/{{ $borrow->id }}/reject">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lý Do Từ Chối <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror" rows="4" placeholder="Nhập lý do từ chối..." required></textarea>
                        @error('rejection_reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ Chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .timeline-item {
        padding-bottom: 1rem;
        border-left: 2px solid #e9ecef;
        padding-left: 0;
    }
    
    .timeline-item:last-child {
        border-left: none;
    }
</style>

@endsection
