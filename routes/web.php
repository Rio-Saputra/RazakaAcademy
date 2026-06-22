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

    Route::post('notifications/{id}/read', [UserTryoutController::class, 'readNotification'])->name('notifications.read');
    Route::post('notifications/read-all', [UserTryoutController::class, 'readAllNotifications'])->name('notifications.read_all');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('paket', PackageController::class);
    Route::resource('tryout', TryoutController::class);
    Route::get('soal/export-pdf/{tryout_id}', [QuestionController::class, 'exportPdf'])->name('soal.export_pdf');
    Route::resource('soal', QuestionController::class);
    
    Route::post('bank-soal/import-pdf', [\App\Http\Controllers\Admin\BankSoalController::class, 'importPdf'])->name('bank-soal.import_pdf');
    Route::post('bank-soal/destroy-all', [\App\Http\Controllers\Admin\BankSoalController::class, 'destroyAll'])->name('bank-soal.destroy_all');
    Route::resource('kategori-bank-soal', \App\Http\Controllers\Admin\KategoriBankSoalController::class);
    Route::resource('bank-soal', \App\Http\Controllers\Admin\BankSoalController::class);
    Route::post('tryout/generate', [TryoutController::class, 'generate'])->name('tryout.generate');
    
    Route::get('kelola_user', [App\Http\Controllers\AdminController::class, 'kelolaUser'])->name('kelola_user');
    Route::post('kelola_user', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('user.store');
    Route::delete('kelola_user/{id}', [App\Http\Controllers\AdminController::class, 'destroyUser'])->name('user.destroy');
    Route::get('transaksi', [App\Http\Controllers\AdminController::class, 'transaksi'])->name('transaksi');
    Route::get('profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('profile.update');
});

// Webhook Midtrans (No auth, CSRF excluded in bootstrap/app.php)
Route::post('midtrans-notification', [\App\Http\Controllers\User\MidtransController::class, 'handleNotification'])
    ->name('midtrans.notification');

// Tautan Bantuan Lokal: Selesaikan Semua Transaksi Pending Instan di Localhost
Route::get('debug-settle-all', function() {
    $transactions = \App\Models\Transaction::where('status', 'pending')->get();
    $count = 0;
    foreach ($transactions as $t) {
        $t->update(['status' => 'success']);
        
        $package = $t->package;
        if ($package) {
            foreach ($package->tryouts as $tryout) {
                $exists = \App\Models\TryoutAttempt::where('user_id', $t->user_id)
                    ->where('tryout_id', $tryout->id)
                    ->where('transaction_id', $t->id)
                    ->exists();

                if (!$exists) {
                    \App\Models\TryoutAttempt::create([
                        'user_id' => $t->user_id,
                        'tryout_id' => $tryout->id,
                        'transaction_id' => $t->id,
                        'status' => 'available',
                        'attempt_count' => 0,
                        'max_attempt' => $tryout->batas_pengerjaan ?? 1
                    ]);
                }
            }
        }
        $count++;
    }
    return "Berhasil mengaktifkan {$count} transaksi pending secara lokal! Silakan cek kembali halaman tryout/transaksi.";
});

// Tautan Bantuan Lokal: Kembalikan semua transaksi sukses ke status pending agar dapat diuji coba kembali
Route::get('debug-unsettle-all', function() {
    $transactions = \App\Models\Transaction::where('status', 'success')->get();
    $count = 0;
    foreach ($transactions as $t) {
        $t->update(['status' => 'pending']);
        // Delete any TryoutAttempt associated with this transaction
        \App\Models\TryoutAttempt::where('user_id', $t->user_id)
            ->where('transaction_id', $t->id)
            ->delete();
        $count++;
    }
    return "Berhasil mengembalikan {$count} transaksi sukses ke pending untuk pengujian ulang! Halaman tryout Anda kini dikunci kembali.";
});
