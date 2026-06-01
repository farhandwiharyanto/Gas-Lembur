<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Dashboard User
    Route::get('/dashboard', [\App\Http\Controllers\UserHistoryController::class, 'dashboard'])->name('user.dashboard');

    // Rute user (form lembur)
    Route::get('/', [OvertimeController::class, 'create'])->name('overtime.create');
    Route::post('/submit', [OvertimeController::class, 'store'])->name('overtime.store');
    Route::get('/overtime/{id}/edit', [OvertimeController::class, 'edit'])->name('overtime.edit');
    Route::put('/overtime/{id}', [OvertimeController::class, 'update'])->name('overtime.update');
    Route::get('/overtime/{id}/print', [OvertimeController::class, 'print'])->name('overtime.print');

    // Rute Employee Portal
    Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/history', [\App\Http\Controllers\UserHistoryController::class, 'index'])->name('user.history.index');
    Route::post('/history/bulk-download', [\App\Http\Controllers\UserHistoryController::class, 'bulkDownload'])->name('user.bulk_download');
    Route::get('/perhitungan-lembur', [\App\Http\Controllers\PerhitunganLemburController::class, 'index'])->name('user.perhitungan.index');
    
    // Rute Cuti Karyawan
    Route::group(['prefix' => 'cuti'], function () {
        Route::get('/dashboard', [\App\Http\Controllers\LeaveController::class, 'dashboard'])->name('user.cuti.dashboard');
        Route::get('/', [\App\Http\Controllers\LeaveController::class, 'index'])->name('user.cuti.index');
        Route::get('/create', [\App\Http\Controllers\LeaveController::class, 'create'])->name('user.cuti.create');
        Route::post('/', [\App\Http\Controllers\LeaveController::class, 'store'])->name('user.cuti.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\LeaveController::class, 'edit'])->name('user.cuti.edit');
        Route::put('/{id}', [\App\Http\Controllers\LeaveController::class, 'update'])->name('user.cuti.update');
        Route::delete('/{id}', [\App\Http\Controllers\LeaveController::class, 'destroy'])->name('user.cuti.destroy');
        Route::get('/{id}/print', [\App\Http\Controllers\LeaveController::class, 'print'])->name('user.cuti.print');
    });
    
    // Rute Pimpinan
    Route::group(['prefix' => 'pimpinan'], function () {
        Route::get('/dashboard', [\App\Http\Controllers\PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/approvals', [\App\Http\Controllers\PimpinanController::class, 'approvals'])->name('pimpinan.approvals');
        Route::get('/history', [\App\Http\Controllers\PimpinanController::class, 'history'])->name('pimpinan.history');
        Route::patch('/approve/{id}', [\App\Http\Controllers\PimpinanController::class, 'approve'])->name('pimpinan.approve');
        Route::patch('/reject/{id}', [\App\Http\Controllers\PimpinanController::class, 'reject'])->name('pimpinan.reject');
        Route::patch('/bulk-approve', [\App\Http\Controllers\PimpinanController::class, 'bulkApprove'])->name('pimpinan.bulk_approve');

        // Rute Cuti Pimpinan
        Route::group(['prefix' => 'cuti'], function () {
            Route::get('/approvals', [\App\Http\Controllers\PimpinanLeaveController::class, 'approvals'])->name('pimpinan.cuti.approvals');
            Route::get('/history', [\App\Http\Controllers\PimpinanLeaveController::class, 'history'])->name('pimpinan.cuti.history');
            Route::patch('/approve/{id}', [\App\Http\Controllers\PimpinanLeaveController::class, 'approve'])->name('pimpinan.cuti.approve');
            Route::patch('/reject/{id}', [\App\Http\Controllers\PimpinanLeaveController::class, 'reject'])->name('pimpinan.cuti.reject');
            Route::patch('/bulk-approve', [\App\Http\Controllers\PimpinanLeaveController::class, 'bulkApprove'])->name('pimpinan.cuti.bulk_approve');
        });
    });

    // Rute admin (dashboard & approval) 
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen Lembur
        Route::get('/overtimes', [AdminController::class, 'overtimes'])->name('admin.overtimes.index');
        Route::post('/overtimes/bulk-download', [AdminController::class, 'bulkDownload'])->name('admin.bulk_download');
        Route::patch('/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
        Route::patch('/force-approve/{id}', [AdminController::class, 'forceApprove'])->name('admin.force_approve');
        Route::patch('/reject/{id}', [AdminController::class, 'reject'])->name('admin.reject');
        Route::delete('/overtimes/{id}', [AdminController::class, 'destroyOvertime'])->name('admin.overtimes.destroy');
        
        // Rute Cuti Admin
        Route::group(['prefix' => 'cuti'], function () {
            Route::get('/', [\App\Http\Controllers\AdminLeaveController::class, 'index'])->name('admin.cuti.index');
            Route::patch('/approve/{id}', [\App\Http\Controllers\AdminLeaveController::class, 'approve'])->name('admin.cuti.approve');
            Route::patch('/reject/{id}', [\App\Http\Controllers\AdminLeaveController::class, 'reject'])->name('admin.cuti.reject');
            Route::patch('/force-approve/{id}', [\App\Http\Controllers\AdminLeaveController::class, 'forceApprove'])->name('admin.cuti.force_approve');
            Route::delete('/{id}', [\App\Http\Controllers\AdminLeaveController::class, 'destroy'])->name('admin.cuti.destroy');
        });
        
        // Manajemen Pengguna
        Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{id}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{id}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');

        // Laporan & Export
        Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('admin.reports.export');
    });
});
