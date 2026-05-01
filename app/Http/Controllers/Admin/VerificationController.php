<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class VerificationController extends Controller
{
    public function index()
    {
        $pendingResellers = User::where('role', 'reseller')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $distributors = User::where('role', 'distributor')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.admin.verify', compact('pendingResellers', 'distributors'));
    }

    public function approve(Request $request)
    {
        $request->validate([
            'reseller_id' => 'required|exists:users,id',
            'distributor_id' => 'required|exists:users,id',
        ]);

        $reseller = User::findOrFail($request->reseller_id);
        
        $reseller->update([
            'status' => 'active',
            'upline_id' => $request->distributor_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reseller berhasil disetujui.'
        ]);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'reseller_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:500',
        ]);

        $reseller = User::findOrFail($request->reseller_id);
        
        $reseller->update([
            'status' => 'rejected',
            'reject_reason' => $request->reason,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran reseller telah ditolak.'
        ]);
    }
}
