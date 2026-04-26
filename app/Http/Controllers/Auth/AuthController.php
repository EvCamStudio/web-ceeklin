<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.role', ['role' => 'admin']);
            } elseif ($user->role === 'distributor') {
                return redirect()->route('dashboard.role', ['role' => 'distributor']);
            } elseif ($user->role === 'reseller') {
                if ($user->status === 'pending') {
                    return redirect()->route('activation');
                }
                return redirect()->route('dashboard.role', ['role' => 'reseller']);
            }

            return redirect()->intended('/dashboard/reseller');
        }

        return back()->withErrors([
            'username' => 'Identitas tersebut tidak cocok dengan data kami.',
        ])->onlyInput('username');
    }

    public function showRegister()
    {
        return view('auth.register-reseller');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            // Step 1
            'nik' => 'required|string|size:16|unique:users,nik',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'ktp_photo' => 'required|image|max:2048',
            'province_id' => 'required|string',
            'city_id' => 'required|string',
            'district_id' => 'required|string',
            'address' => 'required|string',
            
            // Step 2
            'bank_account_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50|unique:users,bank_account_number',
            
            // Step 3
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di sistem kami.',
            'nik.size' => 'NIK harus tepat 16 digit.',
            'bank_account_number.unique' => 'Nomor rekening ini sudah terdaftar. Gunakan rekening lain.',
            'username.unique' => 'Username ini sudah digunakan, silakan pilih yang lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'required' => 'Bidang ini wajib diisi.',
            'image' => 'File harus berupa gambar (JPG/PNG).',
        ]);

        // Handle file upload
        if ($request->hasFile('ktp_photo')) {
            $validated['ktp_photo'] = $request->file('ktp_photo')->store('ktp_photos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'reseller';
        $validated['status'] = 'pending';

        // Optional: find upline by referral code
        if ($request->referral_code) {
            $upline = User::where('referral_code', $request->referral_code)->first();
            if ($upline) {
                $validated['upline_id'] = $upline->id;
            }
        }

        $user = User::create($validated);

        Auth::login($user);

        return view('auth.register-success');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
