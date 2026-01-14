<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Password reset route - redirect to frontend
Route::get('/password/reset/{token}', function ($token) {
    $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
    $email = request()->query('email');
    
    // Redirect to frontend password reset page with token and email
    return redirect()->away("{$frontendUrl}/reset-password?token={$token}&email=" . urlencode($email));
})->name('password.reset');
