

<?php $__env->startSection('title', 'Chi Tiết Mượn'); ?>

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
                    ($borrow->status === 'renewal_requested' ? 'bg-info' :
                    ($borrow->status === 'return_requested' ? 'bg-warning' :
                    ($borrow->status === 'cancelled' ? 'bg-secondary' : 'bg-dark')))))))); ?>">
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
                    <?php elseif($borrow->status === 'renewal_requested'): ?>
                        <i class="bi bi-arrow-repeat"></i> Yêu Cầu Gia Hạn
                    <?php elseif($borrow->status === 'return_requested'): ?>
                        <i class="bi bi-reply-fill"></i> Yêu Cầu Trả
                    <?php elseif($borrow->status === 'cancelled'): ?>
                        <i class="bi bi-slash-circle"></i> Đã Hủy
                    <?php else: ?>
                        <?php echo e(ucfirst($borrow->status)); ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?php echo e(route('employee.borrowings.index')); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>

    <!-- Main Info -->
    <div class="row mb-4">
        <!-- Left - Request Details -->
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
                                <small class="text-muted d-block">Ngày Tạo</small>
                                <strong><?php echo e($borrow->created_at?->format('d/m/Y H:i') ?? 'N/A'); ?></strong>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Người Yêu Cầu</small>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if($borrow->user): ?>
                                        <?php if($borrow->user->avatar): ?>
                                            <img src="<?php echo e(asset('storage/' . $borrow->user->avatar)); ?>" alt="<?php echo e($borrow->user->name); ?>" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                <i class="bi bi-person text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <strong><?php echo e($borrow->user->name); ?></strong>
                                    <?php else: ?>
                                        <span class="badge bg-warning">⚠️ Người dùng không tồn tại</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <small class="text-muted d-block">Trạng Thái</small>
                                <strong>
                                    <?php if($borrow->status === 'borrowed'): ?>
                                        ✓ Đang Mượn
                                    <?php elseif($borrow->status === 'pending'): ?>
                                        ⏳ Chờ Duyệt
                                    <?php elseif($borrow->status === 'approved'): ?>
                                        ✓ Đã Duyệt
                                    <?php elseif($borrow->status === 'returned'): ?>
                                        ✓ Đã Trả
                                    <?php elseif($borrow->status === 'rejected'): ?>
                                        ✗ Từ Chối
                                    <?php elseif($borrow->status === 'cancelled'): ?>
                                        ✗ Đã Hủy
                                    <?php endif; ?>
                                </strong>
                            </div>
                            <?php if($borrow->approvedBy): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block">Duyệt Bởi</small>
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
                        <small class="text-muted d-block mb-2">Lý Do Mượn</small>
                        <p class="mb-0"><?php echo e($borrow->reason); ?></p>
                    </div>
                </div>
            </div>

            <!-- Equipment Table -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📦 Thiết Bị Mượn (<?php echo e($borrow->items->count()); ?> cái)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 80px;">Ảnh</th>
                                    <th>Model</th>
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
                                                <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="<?php echo e($item->model->name); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border-radius: 4px;">
                                                    <small class="text-muted">N/A</small>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?php echo e($item->model?->name ?? 'N/A'); ?></strong></td>
                                        <td><code><?php echo e($item->serial_number); ?></code></td>
                                        <td><?php echo e($item->model?->brand?->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($item->model?->category?->name ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($item->status === 'borrowed' ? 'info' : ($item->status === 'available' ? 'success' : 'danger')); ?>">
                                                <?php if($item->status === 'available'): ?>
                                                    ✓ Sẵn Sàng
                                                <?php elseif($item->status === 'borrowed'): ?>
                                                    ✓ Đang Mượn
                                                <?php elseif($item->status === 'maintenance'): ?>
                                                    ⚙️ Bảo Trì
                                                <?php elseif($item->status === 'damaged'): ?>
                                                    ✗ Hỏng
                                                <?php elseif($item->status === 'lost'): ?>
                                                    ✗ Mất
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
                </div>
            </div>
        </div>

        <!-- Right - Timeline & Actions -->
        <div class="col-md-4">
            <!-- Timeline Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">📅 Thời Hạn Mượn</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Từ Ngày</small>
                        <strong class="text-primary fs-6"><?php echo e($borrow->start_date?->format('d/m/Y') ?? 'N/A'); ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Đến Ngày</small>
                        <strong class="text-primary fs-6"><?php echo e($borrow->end_date?->format('d/m/Y') ?? 'N/A'); ?></strong>
                    </div>

                    <?php if($borrow->status === 'borrowed'): ?>
                        <div class="alert <?php echo e($borrow->end_date?->isPast() ? 'alert-danger' : 'alert-info'); ?> p-3 mb-0">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="text-uppercase" style="font-size: 0.9rem;">
                                    <?php if($borrow->end_date?->isPast()): ?>
                                        ⚠️ QUÁ HẠN
                                    <?php else: ?>
                                        ✓ CÒN LẠI
                                    <?php endif; ?>
                                </strong>
                                <span class="fs-5">
                                    <strong>
                                        <?php echo e(formatRemainingTime($borrow->end_date)); ?>

                                    </strong>
                                </span>
                            </div>
                            <small>
                                <?php if($borrow->end_date?->isPast()): ?>
                                    Bạn cần trả thiết bị ngay
                                <?php else: ?>
                                    Bạn có <?php echo e(formatRemainingTime($borrow->end_date)); ?> để trả
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php elseif($borrow->actual_return_date): ?>
                        <div class="alert alert-success p-3">
                            <small class="text-muted d-block">Ngày Trả Thực Tế</small>
                            <strong class="text-success fs-6"><?php echo e($borrow->actual_return_date->format('d/m/Y')); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <?php
                $pendingRenewal = $borrow->renewalRequests()->where('status', 'pending')->first();
                $pendingReturn = $borrow->returnRequests()->where('status', 'pending')->first();
            ?>

            <?php if($borrow->status === 'renewal_requested'): ?>
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Yêu Cầu Gia Hạn Đang Chờ Duyệt</strong> - Bạn không thể gia hạn thêm hoặc trả thiết bị cho đến khi yêu cầu gia hạn được xử lý.
                </div>
            <?php elseif($borrow->status === 'return_requested'): ?>
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Yêu Cầu Trả Đang Chờ Xử Lý</strong> - Bạn không thể gia hạn hoặc gửi yêu cầu trả mới cho đến khi yêu cầu trả hiện tại được xử lý.
                </div>
            <?php elseif($borrow->status === 'cancelled'): ?>
                <div class="alert alert-secondary mb-4">
                    <i class="bi bi-slash-circle"></i>
                    <strong>Yêu Cầu Đã Bị Hủy</strong> - Yêu cầu mượn này đã bị hủy và không thể thực hiện hành động nào khác.
                </div>
            <?php endif; ?>

            <?php if($borrow->status === 'borrowed'): ?>
                <div class="card border-0 shadow-sm mb-4 bg-light">
                    <div class="card-body p-4">
                        <h6 class="mb-3"><strong>Hành Động</strong></h6>
                        <div class="d-grid gap-2">
                            <!-- Renew Button -->
                            <form method="POST" action="<?php echo e(route('employee.borrowings.renew', $borrow)); ?>" style="display: contents;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-arrow-repeat"></i> Yêu Cầu Gia Hạn Thêm 7 Ngày
                                </button>
                            </form>

                            <!-- Return Button -->
                            <form method="POST" action="<?php echo e(route('employee.borrowings.return', $borrow)); ?>" style="display: contents;" onsubmit="return confirm('Gửi yêu cầu trả thiết bị đến admin?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle"></i> Yêu Cầu Trả Thiết Bị
                                </button>
                            </form>

                            <!-- Cancel Button -->
                            <form method="POST" action="<?php echo e(route('employee.borrowings.cancel', $borrow)); ?>" style="display: contents;" onsubmit="return confirm('Xác nhận hủy yêu cầu này?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i> Hủy Yêu Cầu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php elseif($borrow->status === 'pending'): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-hourglass-split"></i>
                    <strong class="d-block mt-2">Chờ Duyệt</strong>
                    <small class="text-muted">Yêu cầu của bạn đang chờ duyệt từ quản trị viên</small>
                </div>
            <?php elseif($borrow->status === 'rejected'): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i>
                    <strong class="d-block mt-2">Yêu Cầu Bị Từ Chối</strong>
                    <?php if($borrow->rejection_reason): ?>
                        <small><strong>Lý do:</strong> <?php echo e($borrow->rejection_reason); ?></small>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Borrowing Status Info -->
            <?php if($borrow->status === 'borrowed'): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0">💡 Gợi Ý</h6>
                    </div>
                    <div class="card-body">
                        <ul class="small text-muted mb-0">
                            <li>Bạn có thể gia hạn tối đa 3 lần</li>
                            <li>Mỗi lần gia hạn thêm 7 ngày</li>
                            <li>Trả thiết bị đúng hạn tránh phạt</li>
                            <li>Kiểm tra thiết bị trước khi trả</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/employee/borrowings/show.blade.php ENDPATH**/ ?>