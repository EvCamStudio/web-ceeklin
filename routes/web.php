<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\Distributor\DistributorDashboardController;

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

// Admin Distributor Management Specific Routes
Route::prefix('dashboard/admin')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'overview'])->name('admin.overview');
    Route::get('/mapping', [App\Http\Controllers\Admin\AdminDashboardController::class, 'mapping'])->name('admin.mapping');
    Route::post('/mapping/migrate', [App\Http\Controllers\Admin\AdminDashboardController::class, 'migrateReseller'])->name('admin.mapping.migrate');
    Route::get('/pricing', [App\Http\Controllers\Admin\AdminDashboardController::class, 'pricing'])->name('admin.pricing');
    Route::get('/pricing/update', function() { return redirect()->route('admin.pricing'); });
    Route::post('/pricing/update', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updatePricing'])->name('admin.pricing.update');
    Route::get('/bonus', [App\Http\Controllers\Admin\AdminDashboardController::class, 'bonus'])->name('admin.bonus');
    Route::get('/bonus/update', function() { return redirect()->route('admin.bonus'); });
    Route::post('/bonus/update', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updateBonusSettings'])->name('admin.bonus.update');
    Route::post('/bonus/approve', [App\Http\Controllers\Admin\AdminDashboardController::class, 'approveBonus'])->name('admin.bonus.approve');
    Route::post('/bonus/reject', [App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectBonus'])->name('admin.bonus.reject');
    Route::get('/distributor-orders', [App\Http\Controllers\Admin\AdminDashboardController::class, 'distributorOrders'])->name('admin.distributor-orders');
    Route::post('/distributor-orders/update-status', [App\Http\Controllers\Admin\AdminDashboardController::class, 'updateDistributorOrderStatus'])->name('admin.distributor-orders.update-status');
    Route::get('/sales', [App\Http\Controllers\Admin\AdminDashboardController::class, 'sales'])->name('admin.sales');
    Route::get('/settings', [App\Http\Controllers\Admin\AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::get('/requests', [App\Http\Controllers\Admin\AdminDashboardController::class, 'requests'])->name('admin.requests');
    Route::post('/requests/approve', [App\Http\Controllers\Admin\AdminDashboardController::class, 'approveAdjustment'])->name('admin.requests.approve');
    Route::post('/requests/reject', [App\Http\Controllers\Admin\AdminDashboardController::class, 'rejectAdjustment'])->name('admin.requests.reject');
    
    Route::prefix('distributors')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DistributorController::class, 'index'])->name('admin.distributors.index');
        Route::post('/store', [App\Http\Controllers\Admin\DistributorController::class, 'store'])->name('admin.distributors.store');
    });

    Route::prefix('verify')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\VerificationController::class, 'index'])->name('admin.verify.index');
        Route::post('/approve', [App\Http\Controllers\Admin\VerificationController::class, 'approve'])->name('admin.verify.approve');
        Route::post('/reject', [App\Http\Controllers\Admin\VerificationController::class, 'reject'])->name('admin.verify.reject');
    });
});

// Distributor Dashboard Routes
Route::prefix('dashboard/distributor')->middleware(['auth'])->group(function () {
    Route::get('/', [DistributorDashboardController::class, 'overview'])->name('distributor.overview');
    Route::get('/inventory', [DistributorDashboardController::class, 'inventory'])->name('distributor.inventory');
    Route::post('/inventory', [DistributorDashboardController::class, 'syncInventory'])->name('distributor.inventory.sync');
    Route::get('/incoming-orders', [DistributorDashboardController::class, 'incomingOrders'])->name('distributor.incoming-orders');
    Route::post('/incoming-orders/update-status', [DistributorDashboardController::class, 'updateOrderStatus'])->name('distributor.incoming-orders.update');
    Route::post('/incoming-orders/cancel', [DistributorDashboardController::class, 'cancelOrder'])->name('distributor.incoming-orders.cancel');
    Route::get('/order', [DistributorDashboardController::class, 'order'])->name('distributor.order');
    Route::get('/order/store', function() { return redirect()->route('distributor.order'); }); // Fallback for refreshes
    Route::post('/order/store', [DistributorDashboardController::class, 'storeOrder'])->name('distributor.order.store');
    Route::get('/resellers', [DistributorDashboardController::class, 'resellers'])->name('distributor.resellers');
    Route::get('/sales-map', [DistributorDashboardController::class, 'salesMap'])->name('distributor.sales-map');
    Route::get('/history', [DistributorDashboardController::class, 'history'])->name('distributor.history');
    Route::post('/history/confirm', [DistributorDashboardController::class, 'confirmReceived'])->name('distributor.history.confirm');
    Route::get('/settings', [DistributorDashboardController::class, 'settings'])->name('distributor.settings');
    Route::post('/settings/update-profile', [DistributorDashboardController::class, 'updateProfile'])->name('distributor.settings.profile');
    Route::post('/settings/update-password', [DistributorDashboardController::class, 'updatePassword'])->name('distributor.settings.password');
    Route::post('/settings/update-bank', [DistributorDashboardController::class, 'updateBank'])->name('distributor.settings.bank');
});

// Reseller Dashboard Routes
Route::prefix('dashboard/reseller')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'overview'])->name('reseller.overview');
    Route::get('/order', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'order'])->name('reseller.order');
    Route::get('/order/store', function() { return redirect()->route('reseller.order'); }); // Fallback for refreshes
    Route::post('/order/store', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'storeOrder'])->name('reseller.order.store');
    Route::get('/history', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'history'])->name('reseller.history');
    Route::post('/history/confirm', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'confirmReceived'])->name('reseller.history.confirm');
    Route::get('/referrals', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'referrals'])->name('reseller.referrals');
    Route::get('/settings', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'settings'])->name('reseller.settings');
    Route::post('/settings/update-profile', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'updateProfile'])->name('reseller.settings.profile');
    Route::post('/settings/update-password', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'updatePassword'])->name('reseller.settings.password');
    Route::post('/settings/update-bank', [App\Http\Controllers\Reseller\ResellerDashboardController::class, 'updateBank'])->name('reseller.settings.bank');
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

    // Redirect to named routes if available
    if ($role === 'admin' && in_array($page, ['overview', 'mapping', 'pricing', 'bonus', 'distributor-orders', 'sales', 'settings'])) {
        return redirect()->route("admin.{$page}");
    }

    if ($role === 'distributor') {
        return redirect()->route("distributor.{$page}");
    }

    if ($role === 'reseller') {
        return redirect()->route("reseller.{$page}");
    }

    $view = $page === 'overview'
        ? "dashboard.{$role}"
        : "dashboard.{$role}.{$page}";

    return view($view);
})->name('dashboard.role')->middleware('auth');

Route::view('/register-success', 'auth.register-success')->name('register-success');

Route::view('/components-showcase', 'components-showcase')->name('components-showcase');
