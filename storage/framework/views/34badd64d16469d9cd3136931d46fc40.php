

<?php $__env->startSection('title', 'Duyệt Yêu Cầu Mượn'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="row mb-3">
    <div class="col-sm-6">
        <h3>Quản Lý Yêu Cầu Mượn</h3>
    </div>
</div>

<!-- Filter & Search -->
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên nhân viên..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất Cả Trạng Thái --</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Chờ Duyệt</option>
                    <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Đã Duyệt</option>
                    <option value="borrowed" <?php echo e(request('status') === 'borrowed' ? 'selected' : ''); ?>>Đang Mượn</option>
                    <option value="returned" <?php echo e(request('status') === 'returned' ? 'selected' : ''); ?>>Đã Trả</option>
                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Từ Chối</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Requests Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Danh Sách Yêu Cầu Mượn</h5>
    </div>
    <div class="card-body p-0">
        <?php if($borrows->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="borrowing-table">
                    <thead class="table-light">
                        <tr>
                            <th>Người Mượn</th>
                            <th>Email</th>
                            <th>Thiết Bị</th>
                            <th>Ngày Mượn</th>
                            <th>Ngày Trả</th>
                            <th>Trạng Thái</th>
                            <th class="text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($borrow->user?->name ?? 'N/A'); ?></strong></td>
                                <td><?php echo e($borrow->user?->email ?? 'N/A'); ?></td>
                                <td>
                                    <?php $__empty_1 = true; $__currentLoopData = $borrow->items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <span class="badge text-bg-primary me-1"><?php echo e($item->model?->name ?? 'N/A'); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span class="text-muted">Không có</span>
                                    <?php endif; ?>
                                    <?php if($borrow->items->count() > 2): ?>
                                        <span class="badge text-bg-secondary">+<?php echo e($borrow->items->count() - 2); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($borrow->start_date?->format('d/m/Y') ?? 'N/A'); ?></td>
                                <td><?php echo e($borrow->end_date?->format('d/m/Y') ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge text-bg-<?php echo e($borrow->status === 'pending' ? 'warning' : ($borrow->status === 'approved' ? 'info' : ($borrow->status === 'borrowed' ? 'success' : ($borrow->status === 'rejected' ? 'danger' : 'secondary')))); ?>">
                                        <?php switch($borrow->status):
                                            case ('pending'): ?>
                                                Chờ Duyệt
                                                <?php break; ?>
                                            <?php case ('approved'): ?>
                                                Đã Duyệt
                                                <?php break; ?>
                                            <?php case ('borrowed'): ?>
                                                Đang Mượn
                                                <?php break; ?>
                                            <?php case ('returned'): ?>
                                                Đã Trả
                                                <?php break; ?>
                                            <?php case ('rejected'): ?>
                                                Từ Chối
                                                <?php break; ?>
                                            <?php default: ?>
                                                <?php echo e(ucfirst($borrow->status)); ?>

                                        <?php endswitch; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <!-- Quick Actions Dropdown -->
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.borrowing.show', $borrow)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Chi Tiết
                                        </a>
                                        <?php if($borrow->status === 'pending'): ?>
                                            <form method="POST" action="<?php echo e(route('admin.borrowing.approve', $borrow)); ?>" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Phê duyệt yêu cầu này?');" title="Phê duyệt">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo e($borrow->id); ?>" title="Từ chối">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php elseif($borrow->status === 'approved'): ?>
                                            <form method="POST" action="<?php echo e(route('admin.borrowing.mark-borrowed', $borrow)); ?>" style="display:inline;">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-info" onclick="return confirm('Xác nhận đã giao thiết bị?');" title="Ghi nhận giao">
                                                    <i class="bi bi-arrow-right"></i>
                                                </button>
                                            </form>
                                        <?php elseif($borrow->status === 'borrowed'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#returnModal<?php echo e($borrow->id); ?>" title="Ghi nhận trả">
                                                <i class="bi bi-arrow-left"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card-body text-center text-muted py-4">
                <p class="mb-0">Không có yêu cầu mượn nào phù hợp</p>
            </div>
        <?php endif; ?>
    </div>
    <?php if($borrows->count() > 0): ?>
        <div class="card-footer d-flex justify-content-center">
            <?php echo e($borrows->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Reject & Return Modals -->
<?php $__currentLoopData = $borrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($borrow->status === 'pending'): ?>
    <div class="modal fade" id="rejectModal<?php echo e($borrow->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Từ Chối Yêu Cầu #<?php echo e($borrow->id); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?php echo e(route('admin.borrowing.reject', $borrow)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reason<?php echo e($borrow->id); ?>" class="form-label">Lý Do Từ Chối <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" id="reason<?php echo e($borrow->id); ?>" class="form-control" rows="4" placeholder="Vui lòng nhập lý do từ chối (ít nhất 10 ký tự)..." required></textarea>
                            <small class="form-text text-muted">Lý do này sẽ được gửi cho nhân viên</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Xác Nhận Từ Chối
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if($borrow->status === 'borrowed'): ?>
    <div class="modal fade" id="returnModal<?php echo e($borrow->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ghi Nhận Trả Thiết Bị #<?php echo e($borrow->id); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?php echo e(route('admin.borrowing.mark-returned', $borrow)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="condition<?php echo e($borrow->id); ?>" class="form-label">Tình Trạng Thiết Bị <span class="text-danger">*</span></label>
                            <select name="condition" id="condition<?php echo e($borrow->id); ?>" class="form-select" required>
                                <option value="">-- Chọn Tình Trạng --</option>
                                <option value="good">Tốt</option>
                                <option value="damaged">Hư Hỏng</option>
                                <option value="lost">Mất Thiết Bị</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="damage_notes<?php echo e($borrow->id); ?>" class="form-label">Ghi Chú</label>
                            <textarea name="damage_notes" id="damage_notes<?php echo e($borrow->id); ?>" class="form-control" rows="3" placeholder="Ghi chú tình trạng hư hỏng (nếu có)..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Xác Nhận Trả
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<style>
.btn-group {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}
.btn-group form {
    margin: 0;
}
.btn-group .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.875rem;
}
</style>

<?php $__env->startSection('scripts'); ?>
<script>
// Initialize table manager for borrowing table
document.addEventListener('DOMContentLoaded', function() {
    new TableManager('borrowing-table');
});
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/admin/borrowing/index.blade.php ENDPATH**/ ?>