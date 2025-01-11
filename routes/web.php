<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        // Login routes
        Route::get('/login', [AdminController::class, 'adminLogin'])->name('login');
        Route::post('/login/submit', [AdminController::class, 'adminLoginSubmit'])->name('login.submit');

        // Password reset routes
        Route::get('/password-reset', [AdminController::class, 'adminPasswordReset'])->name('password_reset');
        Route::post('/password-reset/submit', [AdminController::class, 'adminPasswordResetSubmit'])->name('password_reset.submit');
        Route::get('/reset-password/{token}/{email}', [AdminController::class, 'adminResetPasswordShow'])->name('reset_password.show');
        Route::post('/reset-password', [AdminController::class, 'adminResetPasswordUpdate'])->name('reset_password.update');
    });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');
        Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');
        Route::post('/logout', [AdminController::class, 'adminLogout'])->name('logout'); // Changed to POST for security
    });
});
