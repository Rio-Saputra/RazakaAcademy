<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\TryoutController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\User\UserTryoutController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\UserController;

// Welcome Page
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role == 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }

    return view('welcome');
});
// Auth Routes
Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    
    // Forgot Password Routes
    Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password/send-otp', [AuthController::class, 'sendOtp'])->name('password.email');
    Route::post('forgot-password/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify');
    Route::post('forgot-password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// User Routes
Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {
    Route::get('dashboard', [UserTryoutController::class, 'dashboard'])->name('dashboard');
    Route::get('paket', [TransactionController::class, 'paket'])->name('paket');
    Route::post('paket/{id}/beli', [TransactionController::class, 'beli'])->name('paket.beli');
    
    Route::get('tryout', [UserTryoutController::class, 'index'])->name('tryout.index');
    Route::get('tryout/{id}/kerjakan', [UserTryoutController::class, 'kerjakan'])->name('tryout.kerjakan');
    Route::post('tryout/{id}/submit', [UserTryoutController::class, 'submit'])->name('tryout.submit');
    Route::get('tryout/{id}/hasil', [UserTryoutController::class, 'hasil'])->name('tryout.hasil');
    
    Route::get('riwayat', [TransactionController::class, 'riwayat'])->name('riwayat');
    Route::get('riwayat-tryout', [UserTryoutController::class, 'riwayatTryout'])->name('riwayat-tryout');
    
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('paket', PackageController::class);
    Route::resource('tryout', TryoutController::class);
    Route::resource('soal', QuestionController::class);
    
    Route::resource('kategori-bank-soal', \App\Http\Controllers\Admin\KategoriBankSoalController::class);
    Route::resource('bank-soal', \App\Http\Controllers\Admin\BankSoalController::class);
    Route::post('tryout/generate', [TryoutController::class, 'generate'])->name('tryout.generate');
    
    Route::get('kelola_user', [App\Http\Controllers\AdminController::class, 'kelolaUser'])->name('kelola_user');
    Route::delete('kelola_user/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::get('transaksi', [App\Http\Controllers\AdminController::class, 'transaksi'])->name('transaksi');
    Route::get('profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
});
