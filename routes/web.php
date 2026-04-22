<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/specs', 'specs')->name('specs');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/contact', 'contact')->name('contact');


// BACKEND-TODO: Hapus closure route statis ini. Jadikan route resmi ke "AuthController@create" atau sejenisnya.
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// BACKEND-TODO: [URGENT] Hapus bypass routing palsu ini! Integrasikan dengan Spatie Permission & Auth::attempt()
Route::post('/login', function (Request $request) {
    $username = strtolower($request->input('username'));
    
    // Logic Dummy Routing: Admin -> admin, Distributor -> distributor
    if ($username === 'admin' || $username === 'distributor') {
        $request->session()->put('dummy_role', $username);
        return redirect('/dashboard/' . $username);
    }

    // Logic Khusus Reseller (reseller = aktif, reseller2 = butuh aktivasi)
    if ($username === 'reseller' || $username === 'reseller2') {
        $request->session()->put('dummy_role', 'reseller');

        // BACKEND-TODO: Cek status aktivasi user di Database (misal: if(!$user->is_active))
        if ($username === 'reseller2') {
            return redirect('/activation');
        }

        return redirect('/dashboard/reseller');
    }
    
    return back()->withErrors(['username' => 'Mandat Ditolak: Anda memasukkan ID yang tidak terdaftar di sistem prototipe kami.']);
});

// Route Gerbang Aktivasi Reseller Baru
Route::get('/activation', function () {
    return view('auth.activation-gate');
})->name('activation');

// BACKEND-TODO: Ini hanya rute pameran UI. Ganti menggunakan middleware 'role:admin|distributor|reseller' untuk masing-masing panel kontrol.
Route::get('/dashboard/{role}/{page?}', function ($role, $page = 'overview') {
    $allowedRoles = ['admin', 'distributor', 'reseller'];

    // Pages per-role yang diperbolehkan
    $allowedPages = [
        'admin'       => ['overview', 'pricing', 'bonus', 'distributors', 'sales', 'settings'],
        'distributor' => ['overview', 'order', 'inventory', 'resellers', 'sales-map', 'settings'],
        'reseller'    => ['overview', 'order', 'earnings', 'referrals', 'tier', 'settings'],
    ];

    if (!in_array($role, $allowedRoles) || !in_array($page, $allowedPages[$role])) {
        abort(404);
    }

    // Halaman overview tetap di dashboard/{role}.blade.php
    // Sub-halaman lain di dashboard/{role}/{page}.blade.php
    $view = $page === 'overview'
        ? "dashboard.{$role}"
        : "dashboard.{$role}.{$page}";

    return view($view);
})->name('dashboard');

// Route pendaftaran Reseller (menggunakan Alpine JS Stepper)
Route::view('/register', 'auth.register-reseller')->name('register');
Route::post('/register', function () {
    // BACKEND-TODO: Lakukan validasi dan simpan data, lalu redirect ke Checkout Aktivasi
    return view('auth.register-success');
});
