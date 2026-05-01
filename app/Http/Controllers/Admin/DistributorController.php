<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Laravolt\Indonesia\Models\Province;
use Illuminate\Support\Facades\Hash;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = User::where('role', 'distributor')
            ->withCount('resellers')
            ->orderBy('name')
            ->get();

        $provinces = Province::orderBy('name')->get();

        return view('dashboard.admin.distributors', compact('distributors', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'region' => 'required|exists:indonesia_provinces,code',
            'phone' => 'required|string|max:20',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $validated['company_name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'province_id' => $validated['region'],
            'role' => 'distributor',
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun distributor berhasil dibuat.'
        ]);
    }
}
