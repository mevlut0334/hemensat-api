<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminSubscriptionViewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\PageController;

// Anasayfa
Route::get('/', function () {
    return view('welcome');
});



// Statik Sayfalar
Route::get('/subscription', [PageController::class, 'subscription'])->name('subscription');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/kvkk', [PageController::class, 'kvkk'])->name('kvkk');

// =============================================================================
// ADMİN GİRİŞ ROTALARI
// =============================================================================

// Login sayfası - 'web' middleware grubu otomatik uygulanır
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// =============================================================================
// ADMİN PANEL ROTALARI (Auth gerekli)
// =============================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Çıkış
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Abonelik yönetimi
    Route::get('/subscriptions', [AdminSubscriptionViewController::class, 'index'])
        ->name('subscriptions.index');

    // Abonelik durumu değiştirme (AJAX)
    Route::post('/subscriptions/users/{user}/toggle', [AdminSubscriptionViewController::class, 'toggle']);
});

// Admin root'a erişim - dashboard'a yönlendir
Route::get('/admin', function () {
    return redirect()->route('admin.dashboard');
})->middleware('auth');
