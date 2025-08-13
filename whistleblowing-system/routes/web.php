<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
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
    Route::middleware('role:admin,moderator,investigator')->group(function () {
        Route::resource('reports', AdminReportController::class);
        Route::patch('reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.status.update');
        Route::post('reports/{report}/comments', [AdminReportController::class, 'addComment'])->name('reports.comments.store');
        Route::get('reports/{report}/attachments/{attachment}/download', [AdminReportController::class, 'downloadAttachment'])->name('reports.attachments.download');
    });
    
    // Categories Management (Admin and Moderators only)
    Route::middleware('role:admin,moderator')->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminReportController::class, 'categories'])->name('index');
        Route::post('/', [AdminReportController::class, 'storeCategory'])->name('store');
        Route::patch('/{category}', [AdminReportController::class, 'updateCategory'])->name('update');
        Route::delete('/{category}', [AdminReportController::class, 'destroyCategory'])->name('destroy');
    });
    
    // User Management (Admin only)
    Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminReportController::class, 'users'])->name('index');
        Route::post('/', [AdminReportController::class, 'storeUser'])->name('store');
        Route::patch('/{user}', [AdminReportController::class, 'updateUser'])->name('update');
        Route::patch('/{user}/status', [AdminReportController::class, 'toggleUserStatus'])->name('toggle-status');
    });
    
    // Analytics & Reports (Admin and Moderators only)
    Route::middleware('role:admin,moderator')->prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [DashboardController::class, 'analytics'])->name('index');
        Route::get('/export', [DashboardController::class, 'export'])->name('export');
    });
});
