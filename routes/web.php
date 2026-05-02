<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RegionController;

// Region Routes (API)
Route::get('/api/provinces', [RegionController::class, 'getProvinces']);
Route::get('/api/cities', [RegionController::class, 'getCities']);
Route::get('/api/districts', [RegionController::class, 'getDistricts']);

// Validation API
Route::get('/api/check-nik', [App\Http\Controllers\Auth\ValidationController::class, 'checkNik']);
Route::get('/api/check-username', [App\Http\Controllers\Auth\ValidationController::class, 'checkUsername']);
Route::get('/api/check-bank', [App\Http\Controllers\Auth\ValidationController::class, 'checkBankAccount']);

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/specs', 'specs')->name('specs');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/contact', 'contact')->name('contact');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// Route Gerbang Aktivasi Reseller Baru
Route::get('/activation', function () {
    return view('auth.activation-gate');
})->name('activation')->middleware('auth');

// DASHBOARDS
// Pintu masuk dashboard utama (tanpa parameter)
Route::get('/dashboard', function () {
    return redirect()->route('dashboard.role', ['role' => auth()->user()->role ?? 'reseller']);
})->middleware('auth')->name('dashboard');

// Admin Verification Specific Routes
Route::prefix('dashboard/admin/verify')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\VerificationController::class, 'index'])->name('admin.verify.index');
    Route::post('/approve', [App\Http\Controllers\Admin\VerificationController::class, 'approve'])->name('admin.verify.approve');
    Route::post('/reject', [App\Http\Controllers\Admin\VerificationController::class, 'reject'])->name('admin.verify.reject');
});

// Admin Distributor Management Specific Routes
Route::prefix('dashboard/admin/distributors')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DistributorController::class, 'index'])->name('admin.distributors.index');
    Route::post('/store', [App\Http\Controllers\Admin\DistributorController::class, 'store'])->name('admin.distributors.store');
});

// Dashboard berparameter (admin/distributor/reseller)
Route::get('/dashboard/{role}/{page?}', function ($role, $page = 'overview') {
    $allowedRoles = ['admin', 'distributor', 'reseller'];

    $allowedPages = [
        'admin'       => ['overview', 'pricing', 'bonus', 'verify', 'mapping', 'distributors', 'distributor-orders', 'requests', 'sales', 'settings'],
        'distributor' => ['overview', 'inventory', 'resellers', 'order', 'sales-map', 'history', 'incoming-orders', 'settings'],
        'reseller'    => ['overview', 'order', 'history', 'referrals', 'settings'],
    ];

    if (!in_array($role, $allowedRoles) || !in_array($page, $allowedPages[$role])) {
        abort(404);
    }

    $view = $page === 'overview'
        ? "dashboard.{$role}"
        : "dashboard.{$role}.{$page}";

    return view($view);
})->name('dashboard.role')->middleware('auth');

Route::view('/register-success', 'auth.register-success')->name('register-success');

Route::view('/components-showcase', 'components-showcase')->name('components-showcase');
