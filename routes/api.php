<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AnalyticsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API routes (Anonymous reporting)
Route::prefix('v1')->group(function () {
    
    // Public report submission (no authentication required)
    Route::middleware(['api.ratelimit:10,1'])->group(function () {
        Route::post('reports', [ReportController::class, 'store']);
        Route::get('reports/{reference}/track', [ReportController::class, 'track']);
        Route::get('categories/public', [CategoryController::class, 'publicList']);
    });
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::middleware(['api.ratelimit:5,1'])->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('register', [AuthController::class, 'register']);
            Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
            Route::post('reset-password', [AuthController::class, 'resetPassword']);
        });
        
        Route::middleware(['auth:sanctum', 'api.ratelimit:30,1'])->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('user', [AuthController::class, 'user']);
            Route::patch('user', [AuthController::class, 'updateProfile']);
            Route::post('change-password', [AuthController::class, 'changePassword']);
        });
    });

    // Protected API routes (Authentication required)
    Route::middleware(['auth:sanctum', 'api.ratelimit:60,1'])->group(function () {
        
        // Dashboard and Analytics
        Route::middleware('role:admin,moderator,investigator')->group(function () {
            Route::get('dashboard/stats', [DashboardController::class, 'stats']);
            Route::get('dashboard/recent-reports', [DashboardController::class, 'recentReports']);
            Route::get('dashboard/my-assigned', [DashboardController::class, 'myAssignedReports']);
        });

        Route::middleware('role:admin,moderator')->group(function () {
            Route::get('analytics/overview', [AnalyticsController::class, 'overview']);
            Route::get('analytics/trends', [AnalyticsController::class, 'trends']);
            Route::get('analytics/categories', [AnalyticsController::class, 'categoryStats']);
            Route::get('analytics/export', [AnalyticsController::class, 'export']);
        });
        
        // Reports Management
        Route::middleware('role:admin,moderator,investigator')->prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index']);
            Route::get('/{report}', [ReportController::class, 'show']);
            Route::patch('/{report}', [ReportController::class, 'update']);
            Route::patch('/{report}/status', [ReportController::class, 'updateStatus']);
            Route::patch('/{report}/assign', [ReportController::class, 'assign']);
            Route::delete('/{report}', [ReportController::class, 'destroy'])->middleware('role:admin');
            
            // Report Comments
            Route::prefix('{report}/comments')->group(function () {
                Route::get('/', [CommentController::class, 'index']);
                Route::post('/', [CommentController::class, 'store']);
                Route::patch('/{comment}', [CommentController::class, 'update']);
                Route::delete('/{comment}', [CommentController::class, 'destroy']);
            });
            
            // Report Attachments
            Route::prefix('{report}/attachments')->group(function () {
                Route::get('/', [AttachmentController::class, 'index']);
                Route::post('/', [AttachmentController::class, 'store']);
                Route::get('/{attachment}/download', [AttachmentController::class, 'download']);
                Route::delete('/{attachment}', [AttachmentController::class, 'destroy']);
            });
        });
        
        // Categories Management
        Route::middleware('role:admin,moderator')->prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::get('/{category}', [CategoryController::class, 'show']);
            Route::patch('/{category}', [CategoryController::class, 'update']);
            Route::delete('/{category}', [CategoryController::class, 'destroy']);
            Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus']);
        });
        
        // User Management
        Route::middleware('role:admin')->prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::patch('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
            Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus']);
            Route::patch('/{user}/reset-password', [UserController::class, 'resetPassword']);
        });
        
        // Investigators list for assignment
        Route::middleware('role:admin,moderator')->get('investigators', [UserController::class, 'investigators']);
    });
});

// Fallback route for API
Route::fallback(function () {
    return response()->json([
        'error' => 'API endpoint not found',
        'message' => 'The requested API endpoint does not exist.'
    ], 404);
});