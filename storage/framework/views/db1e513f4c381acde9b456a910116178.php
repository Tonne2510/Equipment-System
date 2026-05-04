

<?php $__env->startSection('title', 'Chi Tiết Yêu Cầu Mượn'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div>
                <h1 class="h3 mb-1">Yêu Cầu Mượn #<?php echo e(str_pad($borrow->id, 5, '0', STR_PAD_LEFT)); ?></h1>
                <div class="badge text-white p-2 <?php echo e($borrow->status === 'borrowed' ? 'bg-primary' :
                    ($borrow->status === 'pending' ? 'bg-warning' :
                    ($borrow->status === 'approved' ? 'bg-info' :
                    ($borrow->status === 'returned' ? 'bg-success' :
                    ($borrow->status === 'rejected' ? 'bg-danger' :
                    ($borrow->status === 'cancelled' ? 'bg-secondary' : 'bg-dark')))))); ?>">
                    <?php if($borrow->status === 'borrowed'): ?>
                        <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                    <?php elseif($borrow->status === 'pending'): ?>
                        <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                    <?php elseif($borrow->status === 'approved'): ?>
                        <i class="bi bi-check2-circle"></i> Đã Duyệt
                    <?php elseif($borrow->status === 'returned'): ?>
                        <i class="bi bi-check-circle"></i> Đã Trả
                    <?php elseif($borrow->status === 'rejected'): ?>
                        <i class="bi bi-x-circle"></i> Từ Chối
                    <?php elseif($borrow->status === 'cancelled'): ?>
                        <i class="bi bi-slash-circle"></i> Đã Hủy
                    <?php else: ?>
                        <?php echo e(ucfirst($borrow->status)); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('admin.borrowing.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <?php if(!$borrow->user): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger border-0" role="alert">
                <div class="d-flex align-items-start">
                    <i class="bi bi-exclamation-triangle-fill me-3 mt-1" style="font-size: 1.5rem;"></i>
                    <div>
                        <h5 class="alert-heading mb-1"><i class="bi bi-exclamation-triangle-fill"></i> ⚠️ Dữ Liệu Bị Hỏng - Người Dùng Không Tồn Tại</h5>
                        <p class="mb-2">Người dùng của yêu cầu này (ID: <code><?php echo e($borrow->user_id); ?></code>) đã bị xóa khỏi hệ thống nhưng yêu cầu mượn vẫn còn trong database.</p>
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
    <?php endif; ?>

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
                                <strong><?php echo e($borrow->created_at ? $borrow->created_at->format('d/m/Y H:i:s') : 'N/A'); ?></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">✏️ Lần Cập Nhật</small>
                                <strong><?php echo e($borrow->updated_at ? $borrow->updated_at->format('d/m/Y H:i:s') : 'N/A'); ?></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">👤 Người Yêu Cầu</small>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($borrow->user && $borrow->user->avatar): ?>
                                        <img src="<?php echo e(asset('storage/' . $borrow->user->avatar)); ?>" alt="<?php echo e($borrow->user->name); ?>" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <strong><?php echo e($borrow->user ? $borrow->user->name : '⚠️ Không có'); ?></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">🏷️ ID Yêu Cầu</small>
                                <strong class="text-primary">#<?php echo e(str_pad($borrow->id, 5, '0', STR_PAD_LEFT)); ?></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">📊 Trạng Thái</small>
                                <strong>
                                    <?php if($borrow->status === 'borrowed'): ?>
                                        <span class="badge bg-primary px-3 py-2"><i class="bi bi-hand-thumbs-up"></i> Đang Mượn</span>
                                    <?php elseif($borrow->status === 'pending'): ?>
                                        <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-hourglass-split"></i> Chờ Duyệt</span>
                                    <?php elseif($borrow->status === 'approved'): ?>
                                        <span class="badge bg-info px-3 py-2"><i class="bi bi-check2-circle"></i> Đã Duyệt</span>
                                    <?php elseif($borrow->status === 'returned'): ?>
                                        <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle"></i> Đã Trả</span>
                                    <?php elseif($borrow->status === 'rejected'): ?>
                                        <span class="badge bg-danger px-3 py-2"><i class="bi bi-x-circle"></i> Từ Chối</span>
                                    <?php elseif($borrow->status === 'cancelled'): ?>
                                        <span class="badge bg-secondary px-3 py-2"><i class="bi bi-slash-circle"></i> Đã Hủy</span>
                                    <?php endif; ?>
                                </strong>
                            </div>
                            <?php if($borrow->approvedBy): ?>
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">✅ Duyệt Bởi</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if($borrow->approvedBy->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $borrow->approvedBy->avatar)); ?>" alt="<?php echo e($borrow->approvedBy->name); ?>" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <strong><?php echo e($borrow->approvedBy->name); ?></strong>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Lý Do Mượn -->
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2">💬 Lý Do Mượn</small>
                        <?php if($borrow->reason): ?>
                            <p class="mb-0 bg-light p-3 rounded"><?php echo e($borrow->reason); ?></p>
                        <?php else: ?>
                            <p class="mb-0 bg-light p-3 rounded text-muted"><em>Không có ghi chú</em></p>
                        <?php endif; ?>
                    </div>

                    <!-- Lý Do Từ Chối (if rejected) -->
                    <?php if($borrow->status === 'rejected' && $borrow->rejection_reason): ?>
                    <div class="mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-2"><i class="bi bi-exclamation-circle-fill text-danger"></i> Lý Do Từ Chối</small>
                        <p class="mb-0 bg-danger bg-opacity-10 p-3 rounded text-danger"><?php echo e($borrow->rejection_reason); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Equipment Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📦 Thiết Bị Mượn (<?php echo e($borrow->items->count()); ?> cái)</h5>
                </div>
                <div class="card-body p-0">
                    <?php if($borrow->items->count() > 0): ?>
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
                                <?php $__currentLoopData = $borrow->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($item->image): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="<?php echo e($item->model ? $item->model->name : 'Thiết bị'); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;" title="<?php echo e($item->model ? $item->model->name : 'Thiết bị'); ?>">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 4px;">
                                                    <i class="bi bi-box2 text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?php echo e($item->model ? $item->model->name : '⚠️ N/A'); ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?php echo e($item->id); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="bg-light px-2 py-1 rounded"><?php echo e($item->serial_number); ?></code>
                                        </td>
                                        <td><?php echo e($item->model && $item->model->brand ? $item->model->brand->name : '❓ N/A'); ?></td>
                                        <td><?php echo e($item->model && $item->model->category ? $item->model->category->name : '❓ N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($item->status === 'borrowed' ? 'info' : ($item->status === 'available' ? 'success' : ($item->status === 'maintenance' ? 'warning' : ($item->status === 'damaged' ? 'danger' : ($item->status === 'lost' ? 'danger' : 'secondary'))))); ?> px-2 py-2">
                                                <?php if($item->status === 'available'): ?>
                                                    <i class="bi bi-check-circle"></i> Sẵn Sàng
                                                <?php elseif($item->status === 'borrowed'): ?>
                                                    <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                                                <?php elseif($item->status === 'maintenance'): ?>
                                                    <i class="bi bi-tools"></i> Bảo Trì
                                                <?php elseif($item->status === 'damaged'): ?>
                                                    <i class="bi bi-exclamation-triangle"></i> Hỏng
                                                <?php elseif($item->status === 'lost'): ?>
                                                    <i class="bi bi-question-circle"></i> Mất
                                                <?php else: ?>
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $item->status))); ?>

                                                <?php endif; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Không có thiết bị trong yêu cầu này</p>
                    </div>
                    <?php endif; ?>
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
                    <?php if($borrow->user): ?>
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <?php if($borrow->user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $borrow->user->avatar)); ?>" alt="<?php echo e($borrow->user->name); ?>" class="rounded-circle" style="width: 48px; height: 48px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-0"><?php echo e($borrow->user->name); ?></h6>
                                    <small class="text-muted">ID: <?php echo e($borrow->user->id); ?></small>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Email</small>
                            <strong><a href="mailto:<?php echo e($borrow->user->email); ?>"><?php echo e($borrow->user->email); ?></a></strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Điện Thoại</small>
                            <strong><?php echo e($borrow->user->phone ?? '📞 Chưa cập nhật'); ?></strong>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted d-block mb-1">Vai Trò</small>
                            <?php if($borrow->user->role): ?>
                                <span class="badge bg-<?php echo e($borrow->user->role->name === 'admin' ? 'danger' : ($borrow->user->role->name === 'manager' ? 'warning' : 'info')); ?> px-3 py-2">
                                    <?php if($borrow->user->role->name === 'admin'): ?>
                                        <i class="bi bi-shield-lock"></i> Admin
                                    <?php elseif($borrow->user->role->name === 'manager'): ?>
                                        <i class="bi bi-person-badge"></i> Quản Lý
                                    <?php else: ?>
                                        <i class="bi bi-person-check"></i> Nhân Viên
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">❓ Chưa phân công</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
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
                            <?php if(in_array($borrow->status, ['pending', 'rejected'])): ?>
                                <form method="POST" action="<?php echo e(route('admin.borrowing.destroy', $borrow)); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Xóa yêu cầu mượn này? Hành động không thể hoàn tác.');">
                                        <i class="bi bi-trash"></i> Xóa Yêu Cầu
                                    </button>
                                </form>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="bi bi-lock"></i> Không Thể Xóa (<?php echo e(ucfirst($borrow->status)); ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                            <?php if($borrow->start_date): ?>
                                <?php echo e($borrow->start_date->format('d/m/Y')); ?> 
                                <span class="text-muted small">(<?php echo e($borrow->start_date->format('l')); ?>)</span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">📌 Đến Ngày</small>
                        <strong class="text-primary fs-6">
                            <?php if($borrow->end_date): ?>
                                <?php echo e($borrow->end_date->format('d/m/Y')); ?>

                                <span class="text-muted small">(<?php echo e($borrow->end_date->format('l')); ?>)</span>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">⏱️ Thời Hạn Mượn</small>
                        <strong>
                            <?php if($borrow->start_date && $borrow->end_date): ?>
                                <?php echo e($borrow->end_date->diffInDays($borrow->start_date) + 1); ?> ngày
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </strong>
                    </div>

                    <?php if($borrow->status === 'borrowed'): ?>
                        <div class="alert alert-<?php echo e($borrow->end_date && $borrow->end_date->isPast() ? 'danger' : 'info'); ?> p-3 mb-0 border-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-uppercase" style="font-size: 0.9rem;">
                                    <?php if($borrow->end_date && $borrow->end_date->isPast()): ?>
                                        <i class="bi bi-exclamation-triangle-fill"></i> QUÁ HẠN
                                    <?php else: ?>
                                        <i class="bi bi-check-circle-fill"></i> CÒN LẠI
                                    <?php endif; ?>
                                </strong>
                                <span class="fs-5">
                                    <strong>
                                        <?php if($borrow->end_date && $borrow->end_date->isPast()): ?>
                                            <span class="text-danger"><?php echo e(abs($borrow->end_date->diffInDays(now()))); ?> ngày</span>
                                        <?php else: ?>
                                            <span class="text-success"><?php echo e($borrow->end_date ? $borrow->end_date->diffInDays(now()) : 0); ?> ngày</span>
                                        <?php endif; ?>
                                    </strong>
                                </span>
                            </div>
                            <small>
                                <?php if($borrow->end_date && $borrow->end_date->isPast()): ?>
                                    <i class="bi bi-exclamation-circle"></i> Thiết bị cần được trả ngay lập tức
                                <?php else: ?>
                                    <i class="bi bi-info-circle"></i> Còn <?php echo e($borrow->end_date ? $borrow->end_date->diffInDays(now()) : 0); ?> ngày để trả
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php elseif($borrow->actual_return_date): ?>
                        <div class="alert alert-success p-3 mb-0 border-0">
                            <small class="text-muted d-block mb-1"><i class="bi bi-check-circle-fill"></i> Ngày Trả Thực Tế</small>
                            <strong class="text-success fs-6">
                                <?php echo e($borrow->actual_return_date ? $borrow->actual_return_date->format('d/m/Y') : 'N/A'); ?>

                                <span class="text-muted small">(<?php echo e($borrow->actual_return_date ? $borrow->actual_return_date->format('l') : ''); ?>)</span>
                            </strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Action Panel -->
            <?php if(auth()->user()->isAdmin()): ?>
                <?php if(!$borrow->user): ?>
                    <div class="card border-0 shadow-sm mb-4 border-2 border-warning">
                        <div class="card-header bg-warning text-dark border-bottom">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> ⚠️ Dữ Liệu Hỏng - Vẫn Có Thể Xử Lý</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-warning mb-3 border-0">
                                <p class="small mb-0"><i class="bi bi-info-circle"></i> Người dùng đã bị xóa nhưng yêu cầu vẫn có thể được xử lý thông bình thường.</p>
                                <p class="small mb-0 mt-2 text-muted">Người yêu cầu: <strong>Không có (user_id: <?php echo e($borrow->user_id ?? 'NULL'); ?>)</strong></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if($borrow->status === 'pending'): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark border-bottom">
                            <h5 class="mb-0"><i class="bi bi-hourglass-split"></i> Phê Duyệt Yêu Cầu</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!$borrow->user): ?>
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Hãy cân nhắc trước khi duyệt!
                                </div>
                            <?php endif; ?>
                            <p class="text-muted small mb-3">Xác nhận duyệt hay từ chối yêu cầu mượn này.</p>
                            <form method="POST" class="d-grid gap-2">
                                <?php echo csrf_field(); ?>
                                <button type="submit" formaction="<?php echo e(route('admin.borrowing.approve', $borrow)); ?>" class="btn btn-success" onclick="return confirm('Phê duyệt yêu cầu mượn này?<?php echo e($borrow->user ? '' : ' (Cảnh báo: Người dùng không tồn tại)'); ?>');">
                                    <i class="bi bi-check-circle"></i> Phê Duyệt
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="bi bi-x-circle"></i> Từ Chối
                                </button>
                            </form>
                        </div>
                    </div>
                <?php elseif($borrow->status === 'approved'): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-info text-white border-bottom">
                            <h5 class="mb-0"><i class="bi bi-check-circle"></i> Ghi Nhận Giao</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!$borrow->user): ?>
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Vẫn có thể ghi nhận giao.
                                </div>
                            <?php endif; ?>
                            <p class="text-muted small mb-3">Xác nhận đã giao thiết bị cho nhân viên.</p>
                            <form method="POST" action="<?php echo e(route('admin.borrowing.mark-borrowed', $borrow)); ?>" class="d-grid">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Xác nhận giao thiết bị?');">
                                    <i class="bi bi-arrow-right-circle"></i> Xác Nhận Đã Giao
                                </button>
                            </form>
                        </div>
                    </div>
                <?php elseif($borrow->status === 'borrowed'): ?>
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-success text-white border-bottom">
                            <h5 class="mb-0"><i class="bi bi-arrow-return-left"></i> Ghi Nhận Trả</h5>
                        </div>
                        <div class="card-body">
                            <?php if(!$borrow->user): ?>
                                <div class="alert alert-warning mb-3 border-0">
                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo: Người dùng không tồn tại. Vẫn có thể ghi nhận trả.
                                </div>
                            <?php endif; ?>
                            <p class="text-muted small mb-3">Ghi nhận trả thiết bị từ nhân viên.</p>
                            <form method="POST" action="<?php echo e(route('admin.borrowing.mark-returned', $borrow)); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label for="condition" class="form-label small">Tình Trạng Thiết Bị</label>
                                    <select name="condition" id="condition" class="form-select <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">-- Chọn --</option>
                                        <option value="good">Tốt</option>
                                        <option value="damaged">Hư Hỏng</option>
                                        <option value="lost">Mất Thiết Bị</option>
                                    </select>
                                    <?php $__errorArgs = ['condition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                <?php endif; ?>
            <?php else: ?>
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
            <?php endif; ?>

            <!-- Pending Renewal Requests -->
            <?php
                $pendingRenewals = $borrow->renewalRequests()->where('status', 'pending')->get();
            ?>
            <?php if($pendingRenewals->count() > 0): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark border-bottom">
                        <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Yêu Cầu Gia Hạn Đang Chờ</h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $pendingRenewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $renewal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                <p class="small text-muted mb-1">Hạn Hiện Tại: <strong><?php echo e($renewal->borrowRequest->end_date->format('d/m/Y')); ?></strong></p>
                                <p class="small text-muted mb-2">Hạn Mới Yêu Cầu: <strong class="text-primary"><?php echo e($renewal->new_end_date->format('d/m/Y')); ?></strong></p>
                                <div class="d-grid gap-2">
                                    <form method="POST" action="<?php echo e(route('admin.borrowing.renewals.approve', $renewal)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Phê duyệt gia hạn?');">
                                            <i class="bi bi-check-circle"></i> Phê Duyệt
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectRenewalModal<?php echo e($renewal->id); ?>">
                                        <i class="bi bi-x-circle"></i> Từ Chối
                                    </button>
                                </div>
                            </div>

                            <!-- Reject Renewal Modal -->
                            <div class="modal fade" id="rejectRenewalModal<?php echo e($renewal->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Từ Chối Gia Hạn</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.borrowing.renewals.reject', $renewal)); ?>">
                                            <?php echo csrf_field(); ?>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Pending Return Requests -->
            <?php
                $pendingReturns = $borrow->returnRequests()->where('status', 'pending')->get();
            ?>
            <?php if($pendingReturns->count() > 0): ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark border-bottom">
                        <h5 class="mb-0"><i class="bi bi-reply-fill"></i> Yêu Cầu Trả Đang Chờ</h5>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $pendingReturns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 pb-3 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                <p class="small text-muted mb-1">Lý Do: <strong><?php echo e($return->reason ?? 'Không có'); ?></strong></p>
                                <p class="small text-muted mb-2">Ghi Chú: <strong><?php echo e($return->notes ?? '-'); ?></strong></p>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveReturnModal<?php echo e($return->id); ?>">
                                        <i class="bi bi-check-circle"></i> Phê Duyệt
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectReturnModal<?php echo e($return->id); ?>">
                                        <i class="bi bi-x-circle"></i> Từ Chối
                                    </button>
                                </div>
                            </div>

                            <!-- Approve Return Modal -->
                            <div class="modal fade" id="approveReturnModal<?php echo e($return->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Phê Duyệt Trả Thiết Bị</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.borrowing.returns.approve', $return)); ?>">
                                            <?php echo csrf_field(); ?>
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
                            <div class="modal fade" id="rejectReturnModal<?php echo e($return->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Từ Chối Trả Thiết Bị</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('admin.borrowing.returns.reject', $return)); ?>">
                                            <?php echo csrf_field(); ?>
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- History -->
            <?php if($borrow->history->count() > 0): ?>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-light border-bottom">
                        <h5 class="mb-0">📜 Lịch Sử</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php $__currentLoopData = $borrow->history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="timeline-item mb-3">
                                    <div class="d-flex gap-3">
                                        <div style="width: 30px; text-align: center; flex-shrink: 0;">
                                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                <i class="bi bi-check"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <strong><?php echo e($history->action); ?></strong>
                                                <span class="badge bg-secondary"><?php echo e($history->action_at?->diffForHumans()); ?></span>
                                            </div>
                                            <small class="text-muted d-block"><?php echo e($history->action_at?->format('d/m/Y H:i')); ?></small>
                                            <?php if($history->notes): ?>
                                                <small class="text-muted d-block mt-1"><?php echo e($history->notes); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
            <form method="POST" action="/admin/borrowing/<?php echo e($borrow->id); ?>/reject">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lý Do Từ Chối <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control <?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4" placeholder="Nhập lý do từ chối..." required></textarea>
                        <?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/admin/borrowing/show.blade.php ENDPATH**/ ?>