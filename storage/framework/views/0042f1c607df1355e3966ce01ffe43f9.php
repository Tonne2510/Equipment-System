

<?php $__env->startSection('title', 'Thiết Bị'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header -->
<div class="mb-5">
    <div class="bg-light rounded-lg p-4 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="mb-1">🏪 Cửa Hàng Thiết Bị</h2>
                <p class="text-muted mb-0">Chọn thiết bị bạn cần mượn</p>
            </div>
            <div class="col-auto">
                <a href="<?php echo e(route('employee.borrowings.index')); ?>" class="btn btn-outline-primary">
                    <i class="bi bi-cart"></i> Lịch Sử Mượn
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('employee.equipment.browse')); ?>" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm thiết bị, mã số..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Tất Cả Danh Mục</option>
                    <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>>
                        <?php echo e($cat->name); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất Cả Trạng Thái</option>
                    <option value="available" <?php echo e(request('status') == 'available' ? 'selected' : ''); ?>>Còn Hàng</option>
                    <option value="borrowed" <?php echo e(request('status') == 'borrowed' ? 'selected' : ''); ?>>Đang Mượn</option>
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

<!-- Active Filter Tags -->
<?php if(request('search') || request('category') || request('status')): ?>
<div class="mb-3">
    <?php if(request('search')): ?>
    <span class="badge bg-primary me-2">
        Tìm kiếm: <?php echo e(request('search')); ?>

        <a href="<?php echo e(route('employee.equipment.browse', array_diff_key(request()->query(), ['search' => '']))); ?>" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    <?php endif; ?>
    <?php if(request('category')): ?>
    <span class="badge bg-info me-2">
        Danh mục: <?php echo e($categories->find(request('category'))->name ?? ''); ?>

        <a href="<?php echo e(route('employee.equipment.browse', array_diff_key(request()->query(), ['category' => '']))); ?>" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    <?php endif; ?>
    <?php if(request('status')): ?>
    <span class="badge bg-warning me-2">
        Trạng thái: <?php echo e(request('status') == 'available' ? 'Còn Hàng' : 'Đang Mượn'); ?>

        <a href="<?php echo e(route('employee.equipment.browse', array_diff_key(request()->query(), ['status' => '']))); ?>" class="ms-1 text-white text-decoration-none">&times;</a>
    </span>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Results -->
<div class="mb-3">
    <p class="text-muted">
        Hiển thị <strong><?php echo e($equipment->count()); ?></strong> thiết bị
        <?php if(request('search') || request('category') || request('status')): ?>
            từ kết quả tìm kiếm
        <?php else: ?>
            trong hệ thống
        <?php endif; ?>
    </p>
</div>

<!-- Equipment Grid -->
<?php if($equipment->count() > 0): ?>
<div class="row g-4 mb-5">
    <?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card h-100 border-0 shadow-sm equipment-card">
            <!-- Image Container -->
            <div class="position-relative bg-light" style="height: 180px; overflow: hidden;">
                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <?php if($item->image): ?>
                        <img src="<?php echo e(asset('storage/' . $item->image)); ?>" alt="<?php echo e($item->model->name); ?>" class="img-fluid" style="max-height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="bi bi-box2 text-muted" style="font-size: 3rem;"></i>
                    <?php endif; ?>
                </div>
                
                <!-- Status Badge -->
                <span class="badge bg-<?php echo e($item->status === 'available' ? 'success' : ($item->status === 'damaged' ? 'danger' : ($item->status === 'lost' ? 'dark' : 'warning'))); ?> position-absolute top-0 end-0 m-2">
                    <?php if($item->status === 'available'): ?>
                        <i class="bi bi-check-circle"></i> Còn Hàng
                    <?php elseif($item->status === 'borrowed'): ?>
                        <i class="bi bi-hand-thumbs-up"></i> Đang Mượn
                    <?php elseif($item->status === 'damaged'): ?>
                        <i class="bi bi-exclamation-circle"></i> Hỏng
                    <?php else: ?>
                        <i class="bi bi-x-circle"></i> Mất
                    <?php endif; ?>
                </span>
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
                <div class="mb-2">
                    <span class="badge bg-light text-dark small"><?php echo e($item->model->category->name); ?></span>
                </div>
                
                <h6 class="card-title fw-bold mb-1"><?php echo e($item->model->name); ?></h6>
                
                <p class="small text-muted mb-2">
                    <i class="bi bi-tag"></i> <?php echo e($item->model->brand->name ?? 'Không rõ'); ?>

                </p>
                
                <p class="small mb-2">
                    <strong>S/N:</strong> <code class="bg-light px-2 py-1"><?php echo e($item->serial_number); ?></code>
                </p>

                <!-- Spacer -->
                <div class="mt-auto"></div>

                <!-- Action Buttons -->
                <div class="btn-group w-100 mt-3" role="group">
                    <a href="<?php echo e(route('employee.borrowings.create', ['equipment' => $item->id])); ?>" 
                       class="btn btn-<?php echo e($item->status === 'available' ? 'primary' : 'secondary'); ?> btn-sm <?php echo e($item->status !== 'available' ? 'disabled' : ''); ?>">
                        <i class="bi bi-lg"></i> Mượn
                    </a>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#equipmentModal<?php echo e($item->id); ?>" title="Xem chi tiết">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Detail Modal -->
    <div class="modal fade" id="equipmentModal<?php echo e($item->id); ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e($item->model->name); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Danh Mục:</strong> <?php echo e($item->model->category->name); ?></p>
                    <p><strong>Hãng:</strong> <?php echo e($item->model->brand->name ?? 'Không rõ'); ?></p>
                    <p><strong>Mã Serial:</strong> <code><?php echo e($item->serial_number); ?></code></p>
                    <p><strong>Mã Tài Sản:</strong> <code><?php echo e($item->asset_tag ?? 'N/A'); ?></code></p>
                    <p><strong>Ngày Mua:</strong> <?php echo e($item->purchase_date?->format('d/m/Y') ?? 'N/A'); ?></p>
                    <p><strong>Giá Mua:</strong> <?php echo e(number_format($item->purchase_cost, 0)); ?>₫</p>
                    <p><strong>Trạng Thái:</strong> 
                        <span class="badge bg-<?php echo e($item->status === 'available' ? 'success' : 'danger'); ?>">
                            <?php echo e(ucfirst($item->status)); ?>

                        </span>
                    </p>
                    <?php if($item->notes): ?>
                    <p><strong>Ghi Chú:</strong> <?php echo e($item->notes); ?></p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <?php if($item->status === 'available'): ?>
                    <a href="<?php echo e(route('employee.borrowings.create', ['equipment' => $item->id])); ?>" class="btn btn-primary">
                        <i class="bi bi-lg"></i> Mượn Thiết Bị
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php else: ?>
<div class="alert alert-secondary text-center py-5" role="alert">
    <div class="mb-3">
        <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.5;"></i>
    </div>
    <h5>Không Tìm Thấy Thiết Bị</h5>
    <p class="mb-0">Không có thiết bị nào phù hợp với tiêu chí tìm kiếm của bạn</p>
</div>
<?php endif; ?>

<style>
.equipment-card {
    transition: all 0.3s ease;
}

.equipment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.equipment-card img {
    transition: transform 0.3s ease;
}

.equipment-card:hover img {
    transform: scale(1.05);
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/employee/equipment-browse.blade.php ENDPATH**/ ?>