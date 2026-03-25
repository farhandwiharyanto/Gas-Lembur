<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {
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
    
    // Rute Pimpinan
    Route::group(['prefix' => 'pimpinan'], function () {
        Route::get('/dashboard', [\App\Http\Controllers\PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/approvals', [\App\Http\Controllers\PimpinanController::class, 'approvals'])->name('pimpinan.approvals');
        Route::get('/history', [\App\Http\Controllers\PimpinanController::class, 'history'])->name('pimpinan.history');
        Route::patch('/approve/{id}', [\App\Http\Controllers\PimpinanController::class, 'approve'])->name('pimpinan.approve');
        Route::patch('/reject/{id}', [\App\Http\Controllers\PimpinanController::class, 'reject'])->name('pimpinan.reject');
    });

    // Rute admin (dashboard & approval) menggunakan penanganan sederhana di Controller atau callback 
    // Untuk keamanan lebih baik idealnya pakai Middleware terpisah, tapi kita gunakan penanganan dasar dulu:
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Manajemen Lembur
        Route::get('/overtimes', [AdminController::class, 'overtimes'])->name('admin.overtimes.index');
        Route::patch('/approve/{id}', [AdminController::class, 'approve'])->name('admin.approve');
        Route::patch('/force-approve/{id}', [AdminController::class, 'forceApprove'])->name('admin.force_approve');
        Route::patch('/reject/{id}', [AdminController::class, 'reject'])->name('admin.reject');
        Route::delete('/overtimes/{id}', [AdminController::class, 'destroyOvertime'])->name('admin.overtimes.destroy');
        
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
