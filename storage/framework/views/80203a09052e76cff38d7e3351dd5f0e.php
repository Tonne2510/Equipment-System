

<?php $__env->startSection('title', 'Lịch Sử Mượn'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="mb-1">📦 Lịch Sử Mượn Thiết Bị</h2>
            <p class="text-muted mb-0">Quản lý và theo dõi các yêu cầu mượn thiết bị của bạn</p>
        </div>
        <a href="<?php echo e(route('employee.equipment.browse')); ?>" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle"></i> Mượn Thiết Bị Mới
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-4">
    <ul class="nav nav-pills border-bottom pb-2 flex-wrap" role="tablist">
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == '' || request('status') == 'all' ? 'active bg-secondary text-white' : 'bg-secondary text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index')); ?>">
                <i class="bi bi-list"></i> Tất Cả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'pending' ? 'active bg-warning text-dark' : 'bg-warning text-dark opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'pending'])); ?>">
                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'borrowed' ? 'active bg-primary text-white' : 'bg-primary text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'borrowed'])); ?>">
                <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'renewal_requested' ? 'active bg-info text-white' : 'bg-info text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'renewal_requested'])); ?>">
                <i class="bi bi-arrow-repeat"></i> Gia Hạn
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'return_requested' ? 'active bg-warning text-dark' : 'bg-warning text-dark opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'return_requested'])); ?>">
                <i class="bi bi-reply-fill"></i> Yêu Cầu Trả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'returned' ? 'active bg-success text-white' : 'bg-success text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'returned'])); ?>">
                <i class="bi bi-check-circle"></i> Đã Trả
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'rejected' ? 'active bg-danger text-white' : 'bg-danger text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'rejected'])); ?>">
                <i class="bi bi-x-circle"></i> Từ Chối
            </a>
        </li>
        <li class="nav-item me-2">
            <a class="nav-link <?php echo e(request('status') == 'cancelled' ? 'active bg-secondary text-white' : 'bg-secondary text-white opacity-50'); ?>" 
               href="<?php echo e(route('employee.borrowings.index', ['status' => 'cancelled'])); ?>">
                <i class="bi bi-slash-circle"></i> Đã Hủy
            </a>
        </li>
    </ul>
</div>

<!-- Search Form -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="🔍 Tìm theo tên thiết bị..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-search"></i> Tìm
                </button>
            </div>
            <?php if(request('search')): ?>
            <div class="col-md-2">
                <a href="<?php echo e(route('employee.borrowings.index')); ?>" class="btn btn-outline-secondary btn-lg w-100">
                    <i class="bi bi-x-lg"></i> Xóa
                </a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Borrow Requests -->
<?php if($borrowings->count() > 0): ?>
    <?php $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card border-0 shadow-sm mb-3 borrow-card hover-shadow">
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                <!-- Left - Status & Equipment Images -->
                <div class="col-md-2">
                    <div class="mb-3">
                        <span class="badge text-white p-2 w-100 text-center <?php echo e($request->status === 'borrowed' ? 'bg-primary' :
                            ($request->status === 'pending' ? 'bg-warning' :
                            ($request->status === 'returned' ? 'bg-success' :
                            ($request->status === 'rejected' ? 'bg-danger' :
                            ($request->status === 'renewal_requested' ? 'bg-info' :
                            ($request->status === 'return_requested' ? 'bg-warning' :
                            ($request->status === 'cancelled' ? 'bg-secondary' : 'bg-secondary'))))))); ?>">
                            <?php if($request->status === 'borrowed'): ?>
                                <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                            <?php elseif($request->status === 'pending'): ?>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                            <?php elseif($request->status === 'returned'): ?>
                                <i class="bi bi-check-circle"></i> Đã Trả
                            <?php elseif($request->status === 'rejected'): ?>
                                <i class="bi bi-x-circle"></i> Từ Chối
                            <?php elseif($request->status === 'renewal_requested'): ?>
                                <i class="bi bi-arrow-repeat"></i> Yêu Cầu Gia Hạn
                            <?php elseif($request->status === 'return_requested'): ?>
                                <i class="bi bi-reply-fill"></i> Yêu Cầu Trả
                            <?php elseif($request->status === 'cancelled'): ?>
                                <i class="bi bi-slash-circle"></i> Đã Hủy
                            <?php else: ?>
                                <i class="bi bi-question-circle"></i> <?php echo e(ucfirst($request->status)); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <!-- Equipment Images -->
                    <div class="equipment-images">
                        <?php $__empty_1 = true; $__currentLoopData = $request->items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="mb-2">
                                <?php if($item->image): ?>
                                    <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="<?php echo e($item->model->name); ?>" class="img-fluid rounded" style="max-height: 80px; width: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded p-3 text-center" style="height: 80px; display: flex; align-items: center;">
                                        <small class="text-muted">Không có ảnh</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Center - Equipment & Info -->
                <div class="col-md-4">
                    <div class="mb-2">
                        <small class="text-muted">Yêu cầu #<?php echo e(str_pad($request->id, 5, '0', STR_PAD_LEFT)); ?></small>
                    </div>
                    
                    <!-- Equipment List -->
                    <h6 class="mb-3"><strong><?php echo e($request->items->count()); ?> Thiết Bị</strong></h6>
                    <div class="equipment-list" style="max-height: 120px; overflow-y: auto;">
                        <?php $__currentLoopData = $request->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-2 pb-2 border-bottom">
                            <strong class="text-dark small d-block"><?php echo e($item->model->name); ?></strong>
                            <small class="text-muted">S/N: <code><?php echo e($item->serial_number); ?></code></small>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Center-Right - Dates & Time -->
                <div class="col-md-3">
                    <div class="mb-3">
                        <small class="text-muted d-block">📅 Ngày Mượn</small>
                        <strong><?php echo e($request->start_date->format('d/m/Y')); ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">📅 Ngày Dự Trả</small>
                        <strong><?php echo e($request->end_date->format('d/m/Y')); ?></strong>
                    </div>
                    
                    <?php if($request->status === 'borrowed'): ?>
                        <div class="alert <?php echo e($request->end_date < now() ? 'alert-danger' : 'alert-info'); ?> p-2 mb-0">
                            <?php if($request->end_date < now()): ?>
                                <small><strong>⚠️ QUÁ HẠN</strong><br><?php echo e(formatRemainingTime($request->end_date)); ?></small>
                            <?php else: ?>
                                <small><strong>✓ Còn Lại</strong><br><?php echo e(formatRemainingTime($request->end_date)); ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($request->actual_return_date): ?>
                        <div class="mt-2">
                            <small class="text-muted d-block">✓ Trả Thực Tế</small>
                            <strong class="text-success"><?php echo e($request->actual_return_date->format('d/m/Y')); ?></strong>
                        </div>
                    <?php endif; ?>

                    <!-- Status Badge -->
                    <?php if($request->status === 'renewal_requested'): ?>
                        <div class="mt-2">
                            <span class="badge text-bg-info">Yêu cầu gia hạn</span>
                        </div>
                    <?php elseif($request->status === 'return_requested'): ?>
                        <div class="mt-2">
                            <span class="badge text-bg-warning">Yêu cầu trả</span>
                        </div>
                    <?php elseif($request->status === 'cancelled'): ?>
                        <div class="mt-2">
                            <span class="badge text-bg-secondary">Đã hủy</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right - Actions -->
                <div class="col-md-3">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('employee.borrowings.show', $request)); ?>" class="btn btn-primary">
                            <i class="bi bi-eye"></i> Xem Chi Tiết
                        </a>
                        
                        <?php if($request->status === 'borrowed'): ?>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#renewModal<?php echo e($request->id); ?>">
                                <i class="bi bi-arrow-repeat"></i> Gia Hạn
                            </button>
                            <form action="<?php echo e(route('employee.borrowings.return', $request)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Gửi yêu cầu trả thiết bị đến admin?')">
                                    <i class="bi bi-check-circle"></i> Trả Thiết Bị
                                </button>
                            </form>
                        <?php elseif($request->status === 'renewal_requested'): ?>
                            <button type="button" class="btn btn-info w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt Gia Hạn
                            </button>
                        <?php elseif($request->status === 'return_requested'): ?>
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt Trả
                            </button>
                        <?php elseif($request->status === 'cancelled'): ?>
                            <button type="button" class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-slash-circle"></i> Yêu Cầu Đã Hủy
                            </button>
                        <?php elseif($request->status === 'pending'): ?>
                            <button type="button" class="btn btn-warning w-100" disabled>
                                <i class="bi bi-hourglass-split"></i> Chờ Duyệt
                            </button>
                        <?php elseif($request->status === 'rejected'): ?>
                            <button type="button" class="btn btn-danger w-100" disabled>
                                <i class="bi bi-x-circle"></i> Từ Chối
                            </button>
                        <?php elseif($request->status === 'returned'): ?>
                            <button type="button" class="btn btn-success w-100" disabled>
                                <i class="bi bi-check-circle"></i> Đã Trả
                            </button>
                        <?php endif; ?>

                        <?php if($request->status === 'pending' || $request->status === 'borrowed'): ?>
                            <form action="<?php echo e(route('employee.borrowings.cancel', $request)); ?>" method="POST" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Xác nhận hủy yêu cầu này?')">
                                    <i class="bi bi-trash"></i> Hủy
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Renew Modal -->
    <div class="modal fade" id="renewModal<?php echo e($request->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Gia Hạn Mượn Thiết Bị</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Gia hạn thêm 7 ngày để tiếp tục sử dụng thiết bị</p>
                    <div class="alert alert-light border">
                        <strong>Ngày kết thúc hiện tại:</strong> <?php echo e($request->end_date->format('d/m/Y')); ?><br>
                        <strong>Ngày kết thúc mới:</strong> <?php echo e($request->end_date->addDays(7)->format('d/m/Y')); ?>

                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="<?php echo e(route('employee.borrowings.renew', $request)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-repeat"></i> Xác Nhận Gia Hạn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <div class="alert alert-light border text-center py-5">
        <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
        <p class="text-muted mb-0">Không có yêu cầu mượn nào</p>
        <a href="<?php echo e(route('employee.equipment.browse')); ?>" class="btn btn-primary mt-3">
            <i class="bi bi-plus-circle"></i> Mượn Thiết Bị
        </a>
    </div>
<?php endif; ?>

<style>
    .borrow-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    
    .borrow-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .equipment-list::-webkit-scrollbar {
        width: 6px;
    }
    
    .equipment-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .equipment-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
    
    .equipment-list::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/employee/borrowings/index.blade.php ENDPATH**/ ?>