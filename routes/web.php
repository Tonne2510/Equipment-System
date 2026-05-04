<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EquipmentController;
use App\Http\Controllers\Admin\BorrowingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Employee\PortalController;
use App\Http\Controllers\Employee\BorrowRequestController;
use App\Http\Controllers\Employee\IncidentController;
use App\Http\Controllers\ProfileController;

// Home - redirect based on role or to login
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin() || auth()->user()->isManager()) {
            return redirect('/admin/dashboard');
        } else {
            return redirect('/employee/dashboard');
        }
    }
    return redirect()->route('login');
})->name('home');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin,manager'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', UserManagementController::class);
    Route::post('users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');
    Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');

    // Equipment Management
    Route::resource('equipment', EquipmentController::class);

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Borrowing Management
    Route::resource('borrowing', BorrowingController::class)->only(['index', 'show', 'destroy']);
    Route::post('borrowing/{borrow}/approve', [BorrowingController::class, 'approve'])->name('borrowing.approve');
    Route::post('borrowing/{borrow}/reject', [BorrowingController::class, 'reject'])->name('borrowing.reject');
    Route::post('borrowing/{borrow}/mark-borrowed', [BorrowingController::class, 'markBorrowed'])->name('borrowing.mark-borrowed');
    Route::post('borrowing/{borrow}/mark-returned', [BorrowingController::class, 'markReturned'])->name('borrowing.mark-returned');

    // Renewal Requests
    Route::get('borrowing-renewals', [BorrowingController::class, 'renewalRequests'])->name('borrowing.renewals');
    Route::get('borrowing-renewals/{renewal}', [BorrowingController::class, 'showRenewal'])->name('borrowing.renewal_detail');
    Route::post('borrowing-renewals/{renewal}/approve', [BorrowingController::class, 'approveRenewal'])->name('borrowing.renewals.approve');
    Route::post('borrowing-renewals/{renewal}/reject', [BorrowingController::class, 'rejectRenewal'])->name('borrowing.renewals.reject');
    Route::delete('borrowing-renewals/{renewal}', [BorrowingController::class, 'destroyRenewal'])->name('borrowing.renewal_destroy');

    // Return Requests
    Route::get('borrowing-returns', [BorrowingController::class, 'returnRequests'])->name('borrowing.returns');
    Route::get('borrowing-returns/{returnReq}', [BorrowingController::class, 'showReturn'])->name('borrowing.return_detail');
    Route::post('borrowing-returns/{returnReq}/approve', [BorrowingController::class, 'approveReturn'])->name('borrowing.returns.approve');
    Route::post('borrowing-returns/{returnReq}/reject', [BorrowingController::class, 'rejectReturn'])->name('borrowing.returns.reject');
    Route::delete('borrowing-returns/{returnReq}', [BorrowingController::class, 'destroyReturn'])->name('borrowing.return_destroy');

    // Incidents Management
    Route::prefix('incidents')->name('incidents.')->group(function () {
        Route::get('/', [IncidentController::class, 'adminIndex'])->name('index');
        Route::get('/{report}', [IncidentController::class, 'adminShow'])->name('show');
        Route::post('/{report}/assign', [IncidentController::class, 'assignStaff'])->name('assign');
        Route::post('/{report}/resolve', [IncidentController::class, 'resolve'])->name('resolve');
        Route::delete('/{report}', [IncidentController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/utilization', [ReportController::class, 'equipmentUtilization'])->name('utilization');
        Route::get('/borrowing', [ReportController::class, 'borrowingAnalysis'])->name('borrowing');
        Route::get('/violations', [ReportController::class, 'violationReport'])->name('violations');
        Route::get('/penalties', [ReportController::class, 'penaltyReport'])->name('penalties');
        Route::get('/employee-borrowing', [ReportController::class, 'employeeBorrowingReport'])->name('employee-borrowing');
    });
});

// Employee Routes
Route::middleware(['auth', 'verified', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');

    // Browse Equipment
    Route::get('/equipment', [PortalController::class, 'browseEquipment'])->name('equipment.browse');
    Route::get('/equipment/{equipment}', [PortalController::class, 'showEquipment'])->name('equipment.show');

    // My Borrowings
    Route::get('/borrowings', [PortalController::class, 'myBorrowings'])->name('borrowings.index');
    
    // Create Borrow Request (MUST be before {borrow} route)
    Route::get('/borrowings/create', [BorrowRequestController::class, 'create'])->name('borrowings.create');
    Route::post('/borrowings', [BorrowRequestController::class, 'store'])->name('borrowings.store');

    // Show Borrowing Detail
    Route::get('/borrowings/{borrow}', [PortalController::class, 'showBorrowing'])->name('borrowings.show');

    // Request Renewal & Return
    Route::post('/borrowings/{borrow}/renew', [BorrowRequestController::class, 'requestRenewal'])->name('borrowings.renew');
    Route::post('/borrowings/{borrow}/return', [BorrowRequestController::class, 'returnEquipment'])->name('borrowings.return');
    Route::delete('/borrowings/{borrow}/cancel', [BorrowRequestController::class, 'cancelRequest'])->name('borrowings.cancel');

    // Incident Reports
    Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/incidents/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/incidents/{report}', [IncidentController::class, 'show'])->name('incidents.show');
});

// Profile Routes (All authenticated users)
Route::middleware(['auth', 'verified'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('update');
    Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
});

// Authentication routes
require __DIR__ . '/auth.php';
