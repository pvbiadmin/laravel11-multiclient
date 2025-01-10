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

Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])->name('admin.dashboard');
});

Route::get('/admin/login', [AdminController::class, 'adminLogin'])
    ->name('admin.login')
    ->middleware('guest.admin');

Route::post('/admin/login/submit', [AdminController::class, 'adminLoginSubmit'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

Route::get('/admin/password-reset', [AdminController::class, 'adminPasswordReset'])->name('admin.password_reset');
Route::post('/admin/password-reset/submit', [AdminController::class, 'adminPasswordResetSubmit'])->name('admin.password_reset.submit');

Route::get('admin/reset-password/{token}/{email}', [AdminController::class, 'adminResetPasswordShow'])
    ->name('admin.reset_password.show');
Route::post('admin/reset-password', [AdminController::class, 'adminResetPasswordUpdate'])
    ->name('admin.reset_password.update');
