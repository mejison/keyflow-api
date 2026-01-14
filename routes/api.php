<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\TypingTestController;
use App\Http\Controllers\Api\UserSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => '1.0.0',
    ]);
});

// API v1 routes
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

    // Protected routes (must be before social auth routes to avoid conflicts)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
        
        // Typing test routes
        Route::prefix('typing-tests')->group(function () {
            Route::get('/', [TypingTestController::class, 'index']);
            Route::post('/', [TypingTestController::class, 'store']);
            Route::get('/statistics', [TypingTestController::class, 'statistics']);
            Route::get('/recent-activity', [TypingTestController::class, 'recentActivity']);
            Route::get('/progress', [TypingTestController::class, 'progress']);
            Route::get('/personal-bests', [TypingTestController::class, 'personalBests']);
            Route::get('/{id}', [TypingTestController::class, 'show']);
            Route::delete('/{id}', [TypingTestController::class, 'destroy']);
        });
        
        // User settings routes
        Route::get('/settings', [UserSettingsController::class, 'index']);
        Route::put('/settings', [UserSettingsController::class, 'update']);
        Route::delete('/settings', [UserSettingsController::class, 'destroy']);
        
        // User's leaderboard rank (protected)
        Route::get('/leaderboard/my-rank', [LeaderboardController::class, 'myRank']);
    });
    
    // Public leaderboard routes
    Route::prefix('leaderboard')->group(function () {
        Route::get('/wpm', [LeaderboardController::class, 'topByWpm']);
        Route::get('/accuracy', [LeaderboardController::class, 'topByAccuracy']);
        Route::get('/tests', [LeaderboardController::class, 'topByTests']);
        Route::get('/combined', [LeaderboardController::class, 'topCombined']);
    });

    // Social authentication routes
    Route::get('/auth/social/{provider}', [SocialAuthController::class, 'redirectToProvider']);
    Route::get('/auth/social/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);
});
