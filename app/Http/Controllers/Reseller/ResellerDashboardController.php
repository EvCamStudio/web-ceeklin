<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResellerOrder;
use App\Models\User;
use App\Models\Pricing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ResellerDashboardController extends Controller
{
    public function overview()
    {
        $user = Auth::user();
        $totalOrders = ResellerOrder::where('reseller_id', $user->id)->count();
        $completedOrders = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Selesai')->count();
        $pendingOrders = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Menunggu Konfirmasi')->count();
        
        $recentHistory = ResellerOrder::where('reseller_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Target Data
        $targetQty = \App\Models\Setting::where('key', 'monthly_target_qty')->value('value') ?? 1000;
        $targetReward = \App\Models\Setting::where('key', 'monthly_target_reward')->value('value') ?? 2500000;
        
        $currentMonthOrders = ResellerOrder::where('reseller_id', $user->id)
            ->where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        $targetProgress = $targetQty > 0 ? min(($currentMonthOrders / $targetQty) * 100, 100) : 0;
        $neededForTarget = max($targetQty - $currentMonthOrders, 0);

        // Upline Info
        $upline = $user->upline;

        return view('dashboard.reseller', compact(
            'user', 'totalOrders', 'completedOrders', 'pendingOrders', 
            'recentHistory', 'targetQty', 'targetReward', 
            'currentMonthOrders', 'targetProgress', 'neededForTarget', 'upline'
        ));
    }

    public function order()
    {
        $user = Auth::user();
        $upline = $user->upline;
        $resellerPrice = Pricing::where('tier', 'reseller')->latest()->first()->price ?? 1450000;

        return view('dashboard.reseller.order', compact('user', 'upline', 'resellerPrice'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $resellerPrice = Pricing::where('tier', 'reseller')->latest()->first()->price ?? 1450000;
        $totalPrice = $request->quantity * $resellerPrice;

        ResellerOrder::create([
            'order_number' => 'RE-' . strtoupper(bin2hex(random_bytes(3))),
            'reseller_id' => $user->id,
            'distributor_id' => $user->upline_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'Menunggu Konfirmasi',
            'payment_status' => 'Belum Dibayar',
        ]);

        return redirect()->route('reseller.history')->with('success', 'Pesanan berhasil dibuat. Silakan hubungi distributor Anda untuk pembayaran.');
    }

    public function history()
    {
        $user = Auth::user();
        $orders = ResellerOrder::where('reseller_id', $user->id)
            ->latest()
            ->get()
            ->map(function($order) {
                $colors = [
                    'Menunggu Konfirmasi' => 'border-red-400 text-red-700 bg-red-50',
                    'Diproses'            => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                    'Dikirim'             => 'border-blue-500 text-blue-700 bg-blue-50',
                    'Selesai'             => 'border-green-600 text-green-700 bg-green-50',
                    'Ditolak'             => 'border-gray-500 text-gray-700 bg-gray-50',
                ];
                $order->statusClass = $colors[$order->status] ?? 'border-gray-300 text-gray-500 bg-gray-50';
                return $order;
            });

        // Stats for History Tab
        $totalTransaction = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Selesai')->sum('total_price');
        $monthlyTransaction = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $pendingBonus = 0; // Separate module

        return view('dashboard.reseller.history', compact('orders', 'totalTransaction', 'monthlyTransaction', 'pendingBonus'));
    }

    public function confirmReceived(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:reseller_orders,id',
        ]);

        $order = ResellerOrder::where('id', $request->order_id)
            ->where('reseller_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'Dikirim') {
            $order->update(['status' => 'Selesai']);
            return back()->with('success', 'Pesanan telah dikonfirmasi selesai. Terima kasih!');
        }

        return back()->with('error', 'Pesanan tidak dalam status dikirim.');
    }

    public function referrals()
    {
        $user = Auth::user();
        $referrals = User::where('upline_id', $user->id)->where('role', 'reseller')->get();
        
        $totalReferrals = $referrals->count();
        $activeReferrals = $referrals->where('status', 'active')->count();
        $totalCommission = 0; // Separate module

        return view('dashboard.reseller.referrals', compact('user', 'referrals', 'totalReferrals', 'activeReferrals', 'totalCommission'));
    }

    public function settings()
    {
        return view('dashboard.reseller.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $user->update($request->only('name', 'phone', 'address'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->with('error', 'Kata sandi saat ini salah.');
        }

        Auth::user()->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diubah.');
    }

    public function updateBank(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'bank_name' => 'required|string',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
        ]);

        $user->update($request->only('bank_name', 'bank_account_name', 'bank_account_number'));

        return back()->with('success', 'Informasi bank berhasil diperbarui.');
    }
}
