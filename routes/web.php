<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Password reset route (required for API password reset emails)
Route::get('/password/reset/{token}', function ($token) {
    return response()->json([
        'message' => 'This is an API endpoint. Use POST /api/v1/auth/reset-password with this token.',
        'token' => $token,
        'email' => request()->query('email'),
    ]);
})->name('password.reset');
