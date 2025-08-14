<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicReportController;
use Illuminate\Support\Facades\Route;

// Redirect root to public report page
Route::get('/', function () {
    return redirect()->route('public.report.index');
});

// Public Routes (Anonymous Reporting)
Route::prefix('report')->name('public.report.')->group(function () {
    Route::get('/', [PublicReportController::class, 'index'])->name('index');
    Route::get('/submit', [PublicReportController::class, 'create'])->name('create');
    Route::post('/submit', [PublicReportController::class, 'store'])->name('store');
    Route::get('/success/{referenceNumber}', [PublicReportController::class, 'success'])->name('success');
    Route::get('/track', [PublicReportController::class, 'track'])->name('track');
    Route::post('/track', [PublicReportController::class, 'trackReport'])->name('track.submit');
});

// Authentication routes
require __DIR__.'/auth.php';

// Admin Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Reports Management (All roles can access)
    Route::middleware('role:admin,moderator,investigator')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'index'])->name('index');
        Route::get('/create', [AdminReportController::class, 'create'])->name('create');
        Route::post('/', [AdminReportController::class, 'store'])->name('store');
        Route::get('/{report}', [AdminReportController::class, 'show'])->name('show');
        Route::get('/{report}/edit', [AdminReportController::class, 'edit'])->name('edit');
        Route::put('/{report}', [AdminReportController::class, 'update'])->name('update');
        Route::delete('/{report}', [AdminReportController::class, 'destroy'])->name('destroy');
        Route::patch('/{report}/status', [AdminReportController::class, 'updateStatus'])->name('status.update');
        Route::post('/{report}/comments', [AdminReportController::class, 'addComment'])->name('comments.store');
        Route::get('/{report}/attachments/{attachment}/download', [AdminReportController::class, 'downloadAttachment'])->name('attachments.download');
    });
    
    // Categories Management (Admin and Moderators only)
    Route::middleware('role:admin,moderator')->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/status', [CategoryController::class, 'toggleStatus'])->name('status.toggle');
    });
    
    // User Management (Admin only)
    Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserController::class, 'toggleStatus'])->name('status.toggle');
        Route::patch('/{user}/password', [UserController::class, 'changePassword'])->name('password.change');
    });
    
    // Profile Management (All authenticated users)
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::patch('/update', [UserController::class, 'updateProfile'])->name('update');
        Route::patch('/password', [UserController::class, 'updatePassword'])->name('password.update');
    });
    
    // Analytics & Reports (Admin and Moderators only)
    Route::middleware('role:admin,moderator')->prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [DashboardController::class, 'analytics'])->name('index');
        Route::get('/export', [DashboardController::class, 'export'])->name('export');
    });
});
