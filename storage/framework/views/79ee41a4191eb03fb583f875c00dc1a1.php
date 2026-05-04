<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Quản Lý Thiết Bị'); ?> - Hệ Thống</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- OverlayScrollbars CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 250px;
            --sidebar-bg: #2c3e50;
            --body-bg: #ecf0f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--body-bg);
        }

        /* ===== APP WRAPPER ===== */
        .app-wrapper {
            display: flex;
            height: 100vh;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .app-header {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0.5rem 0;
            height: auto;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .app-header .navbar {
            padding: 0.5rem 0;
        }

        .app-header .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color) !important;
        }

        .app-header .nav-icon {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .navbar-nav .nav-link {
            color: #6c757d !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .navbar-badge {
            position: absolute;
            top: -8px;
            right: -5px;
            font-size: 0.7rem;
        }

        /* ===== SIDEBAR ===== */
        .app-sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            width: var(--sidebar-width);
            height: calc(100vh - 60px);
            background-color: var(--sidebar-bg);
            color: #fff;
            overflow-y: auto;
            z-index: 900;
            padding: 20px 0;
            transition: all 0.3s ease;
        }

        .sidebar-wrapper {
            overflow-y: auto;
            height: 100%;
        }

        .nav-label {
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            padding: 10px 15px !important;
            margin: 2px 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #fff !important;
        }

        .nav-link.active {
            background-color: var(--primary-color);
            color: #fff !important;
            font-weight: 600;
        }

        /* ===== MAIN CONTENT ===== */
        .app-main {
            flex: 1;
            margin-left: var(--sidebar-width);
            margin-top: 0;
            overflow-y: auto;
        }

        .app-content-header {
            background-color: #fff;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .app-content {
            padding: 20px;
        }

        /* ===== FOOTER ===== */
        .app-footer {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 15px 20px;
            text-align: center;
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* ===== BREADCRUMB ===== */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
        }

        /* ===== CARD ===== */
        .card {
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* ===== SMALL BOX ===== */
        .small-box {
            border-radius: 0.5rem;
            padding: 20px;
            margin-bottom: 20px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        .small-box-icon {
            width: 80px;
            height: 80px;
            opacity: 0.3;
        }

        .small-box .inner {
            flex: 1;
        }

        .small-box h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .small-box p {
            margin: 5px 0 0 0;
            font-size: 0.9rem;
        }

        /* ===== BADGE ===== */
        .badge {
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 4px;
        }

        .text-bg-primary { background-color: #0d6efd !important; color: #fff !important; }
        .text-bg-success { background-color: #198754 !important; color: #fff !important; }
        .text-bg-warning { background-color: #ffc107 !important; color: #000 !important; }
        .text-bg-danger { background-color: #dc3545 !important; color: #fff !important; }
        .text-bg-info { background-color: #0dcaf0 !important; color: #000 !important; }

        /* ===== BUTTON ===== */
        .btn {
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* ===== TABLE ===== */
        .table {
            background-color: #fff;
            border-collapse: collapse;
        }

        .table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table thead th {
            font-weight: 600;
            color: #2c3e50;
            border: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* ===== TABLE SORTING ===== */
        .sortable-header {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .sortable-header:hover {
            background-color: #e9ecef;
        }

        .sortable-header.sort-asc,
        .sortable-header.sort-desc {
            background-color: #d0d8e0;
            color: #0d6efd;
            font-weight: 600;
        }

        .sort-indicator {
            font-size: 0.85em;
            opacity: 0.7;
        }

        .sortable-header.sort-asc .sort-indicator,
        .sortable-header.sort-desc .sort-indicator {
            opacity: 1;
            font-weight: bold;
        }

        /* ===== FORM ===== */
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.75rem;
            display: block;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 0.625rem 0.875rem;
            font-size: 0.95rem;
            line-height: 1.5;
            min-height: 2.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* ===== CARD ===== */
        .card {
            border: none;
            border-radius: 6px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }

        .card-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            background-color: #f8f9fa;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-footer {
            border-top: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
        }

        /* ===== ALERT ===== */
        .alert {
            border-radius: 4px;
            border: none;
            margin-bottom: 1.5rem;
        }

        /* ===== HEADINGS ===== */
        h1, h2, h3, h4, h5, h6 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .app-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
                border-bottom: 1px solid #dee2e6;
            }

            .app-main {
                margin-left: 0;
            }

            .app-content {
                padding: 15px;
            }

            .small-box {
                flex-direction: column;
                text-align: center;
            }
        }

        /* ===== PAGINATION ===== */
        .pagination {
            justify-content: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .page-link {
            border-radius: 4px;
            margin: 0 2px;
        }
    </style>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body>
    <div class="app-wrapper">
        <!-- HEADER -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="/" class="nav-link"><i class="bi bi-house"></i> Trang Chủ</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" role="button">
                            <i class="bi bi-search"></i>
                        </a>
                    </li>

                    <?php if(auth()->check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell-fill"></i>
                                <span class="badge text-bg-danger navbar-badge">5</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="notificationDropdown">
                                <li class="dropdown-header">5 Thông báo</li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="#" class="dropdown-item">
                                    <i class="bi bi-info-circle"></i> Có yêu cầu mượn mới
                                </a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span class="d-none d-md-inline ms-2"><?php echo e(auth()->user()->name); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" aria-labelledby="userDropdown">
                                <li class="dropdown-header">Hồ sơ người dùng</li>
                                <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="bi bi-person"></i> Thông tin cá nhân
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button class="dropdown-item" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('login')); ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- SIDEBAR -->
        <?php if(auth()->check()): ?>
            <aside class="app-sidebar shadow">
                <div class="sidebar-wrapper">
                    <nav class="mt-2">
                        <ul class="nav sidebar-menu flex-column" role="navigation">
                            <?php if(auth()->user()->isAdmin() || auth()->user()->isManager()): ?>
                                <div class="nav-label">DASHBOARD</div>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i class="bi bi-speedometer"></i> Bảng Điều Khiển
                                    </a>
                                </li>

                                <div class="nav-label">QUẢN LÝ</div>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.equipment.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.equipment.index')); ?>">
                                        <i class="bi bi-laptop"></i> Thiết Bị
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.borrowing.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.borrowing.index')); ?>">
                                        <i class="bi bi-hand-index"></i> Yêu Cầu Mượn
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.borrowing.renewals') ? 'active' : ''); ?>" href="<?php echo e(route('admin.borrowing.renewals')); ?>" style="padding-left: 2rem;">
                                        <i class="bi bi-arrow-repeat"></i> Gia Hạn
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.borrowing.returns') ? 'active' : ''); ?>" href="<?php echo e(route('admin.borrowing.returns')); ?>" style="padding-left: 2rem;">
                                        <i class="bi bi-box-arrow-left"></i> Trả Thiết Bị
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.incidents.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.incidents.index')); ?>">
                                        <i class="bi bi-exclamation-triangle"></i> Sự Cố
                                    </a>
                                </li>

                                <div class="nav-label">HỆ THỐNG</div>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.users.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin.users.index')); ?>">
                                        <i class="bi bi-people"></i> Người Dùng
                                    </a>
                                </li>

                                <div class="nav-label">BÁO CÁO</div>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.reports.utilization') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.utilization')); ?>">
                                        <i class="bi bi-graph-up"></i> Sử Dụng Thiết Bị
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.reports.borrowing') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.borrowing')); ?>">
                                        <i class="bi bi-pie-chart"></i> Phân Tích Mượn
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.reports.violations') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.violations')); ?>">
                                        <i class="bi bi-ban"></i> Vi Phạm
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.reports.penalties') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.penalties')); ?>">
                                        <i class="bi bi-file-earmark-text"></i> Phí Phạt
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('admin.reports.employee-borrowing') ? 'active' : ''); ?>" href="<?php echo e(route('admin.reports.employee-borrowing')); ?>">
                                        <i class="bi bi-person-check"></i> Mượn Theo Nhân Viên
                                    </a>
                                </li>
                            <?php else: ?>
                                <div class="nav-label">MENU NHÂN VIÊN</div>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('employee.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('employee.dashboard')); ?>">
                                        <i class="bi bi-speedometer"></i> Bảng Điều Khiển
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('employee.equipment.*') ? 'active' : ''); ?>" href="<?php echo e(route('employee.equipment.browse')); ?>">
                                        <i class="bi bi-laptop"></i> Thiết Bị
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('employee.borrowings.*') ? 'active' : ''); ?>" href="<?php echo e(route('employee.borrowings.index')); ?>">
                                        <i class="bi bi-hand-index"></i> Yêu Cầu Mượn
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Route::is('employee.incidents.*') ? 'active' : ''); ?>" href="<?php echo e(route('employee.incidents.index')); ?>">
                                        <i class="bi bi-exclamation-triangle"></i> Báo Cáo Sự Cố
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </aside>
        <?php endif; ?>

        <!-- MAIN CONTENT -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                                <li class="breadcrumb-item active"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Lỗi!</strong>
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">v1.0</div>
            <strong>Copyright &copy; 2026 - Hệ Thống Quản Lý Thiết Bị</strong> | All rights reserved.
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- OverlayScrollbars JS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>
    <!-- Table Manager JS -->
    <script src="<?php echo e(asset('js/table-manager.js')); ?>"></script>

    <script>
        // Initialize OverlayScrollbars
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: 'os-theme-light',
                    autoHide: 'leave'
                }
            });
        }
    </script>

    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\GMT\Downloads\equipment\resources\views/layouts/app.blade.php ENDPATH**/ ?>