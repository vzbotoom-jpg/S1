<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// ─────────────────────────────────────────────────────────────────────────────
// GUEST ROUTES (Authentication)
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Email Verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
});

// ─────────────────────────────────────────────────────────────────────────────
// AUTHENTICATED ROUTES
// ─────────────────────────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ====================== CHAT ROUTES ======================
    Route::prefix('chat')->name('chat.')->group(function () {
        
        // List semua conversation
        Route::get('/', [ChatController::class, 'index'])->name('index');
        
        // Buat conversation baru
        Route::post('/', [ChatController::class, 'store'])->name('store');
        
        // Tampilkan conversation tertentu
        Route::get('/{conversation}', [ChatController::class, 'show'])
            ->name('show')
            ->where('conversation', '[0-9]+');

        // ←←← ROUTE INI YANG BARU DITAMBAHKAN / DIPERBAIKI
        // Kirim pesan ke conversation
        Route::post('/{conversation}/message', [ChatController::class, 'sendMessage'])
            ->middleware('throttle:60,1')
            ->name('message.store');           // ← Nama route yang benar

        // Hapus conversation
        Route::delete('/{conversation}', [ChatController::class, 'destroy'])
            ->name('destroy')
            ->where('conversation', '[0-9]+');
    });

    // ====================== CONVERSATION HISTORY ======================
    Route::prefix('conversations')->name('conversations.')->group(function () {
        Route::get('/', [ConversationController::class, 'index'])->name('index');
        Route::get('/{conversation}', [ConversationController::class, 'show'])->name('show');
        Route::patch('/{conversation}', [ConversationController::class, 'update'])->name('update');
        Route::delete('/{conversation}', [ConversationController::class, 'destroy'])->name('destroy');
        Route::delete('/', [ConversationController::class, 'bulkDestroy'])->name('bulk-destroy');
       // ✅ BENAR — jadi /conversations/{id}/star
        Route::patch('/{conversation}/star', [ConversationController::class, 'toggleStar'])->name('toggle-star');
    });

    // Profile & Settings
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
         Route::get('/show', [ProfileController::class, 'show'])->name('show'); // ← tambahkan ini
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::patch('/password', [ProfileController::class, 'updatePassword'])->name('password');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::patch('/', [SettingsController::class, 'update'])->name('update');
        Route::delete('/clear-data', [SettingsController::class, 'clearData'])->name('clear-data');
    });
});

// ====================== ADMIN ROUTES ======================
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/users/{user}', [AdminDashboardController::class, 'showUser'])->name('users.show');
        Route::patch('/users/{user}/toggle', [AdminDashboardController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::delete('/users/{user}', [AdminDashboardController::class, 'deleteUser'])->name('users.delete');
        Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
        Route::patch('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
        Route::get('/conversations', [AdminDashboardController::class, 'allConversations'])->name('conversations');
        Route::delete('/conversations/{conversation}', [AdminDashboardController::class, 'deleteConversation'])->name('conversations.delete');
    });

// Fallback 404
Route::fallback(function () {
    return view('errors.404');
});