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

        // Security: Ensure only resellers can see this page
        if ($user->role !== 'reseller') {
            return redirect()->route('dashboard')->with('error', 'Hanya Reseller yang dapat mengakses halaman ini.');
        }

        // Security: Ensure reseller has a distributor (upline)
        if (!$user->upline_id) {
            return redirect()->route('reseller.overview')->with('error', 'Akun Anda belum terhubung dengan Distributor manapun. Silakan hubungi Admin.');
        }

        $upline = $user->upline;
        
        // Define price per PCS (CeeKlin 450ml)
        // Check if there is a global price settings, or use default 20.000
        $price = \App\Models\Pricing::where('tier', 'reseller')->first()->price ?? 15000; 

        return view('dashboard.reseller.order', compact('user', 'upline', 'price'));
    }

    public function storeOrder(Request $request)
    {
        $user = Auth::user();

        // Security: Ensure only resellers can order through this method
        if ($user->role !== 'reseller') {
            return back()->with('error', 'Hanya Reseller yang dapat melakukan pemesanan ini.');
        }

        // Security: Ensure reseller has a distributor (upline)
        if (!$user->upline_id) {
            return back()->with('error', 'Akun Anda belum terhubung dengan Distributor manapun. Silakan hubungi Admin.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:50', // Updated min to 50
        ]);

        $upline = $user->upline;

        // Security: Check if distributor has enough stock
        if ($upline->stock < $request->quantity) {
            return back()->with('error', "Stok distributor Anda saat ini tidak mencukupi (Tersedia: {$upline->stock} PCS). Silakan hubungi distributor Anda.");
        }

        $resellerPrice = Pricing::where('tier', 'reseller')->first()->price ?? 15000;
        $totalPrice = $request->quantity * $resellerPrice;

        ResellerOrder::create([
            'order_number' => 'RE-' . strtoupper(bin2hex(random_bytes(3))),
            'reseller_id' => $user->id,
            'distributor_id' => $user->upline_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'Menunggu Konfirmasi',
            'payment_status' => 'Belum Dibayar',
            'note' => $request->input('shipping_note'), // Map note to model
        ]);

        // Redirect back with order_success to show the success view in the frontend
        return redirect()->route('reseller.order')->with('order_success', true);
    }

    public function history()
    {
        $user = Auth::user();
        $orders = ResellerOrder::with('distributor')
            ->where('reseller_id', $user->id)
            ->latest()
            ->get()
            ->map(function($order) {
                $colors = [
                    'Menunggu Konfirmasi' => 'border-red-400 text-red-700 bg-red-50',
                    'Menunggu Proses'     => 'border-red-400 text-red-700 bg-red-50',
                    'Menunggu'            => 'border-red-400 text-red-700 bg-red-50',
                    'Diproses'            => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                    'Dikemas'             => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                    'Dikirim'             => 'border-blue-500 text-blue-700 bg-blue-50',
                    'Selesai'             => 'border-green-600 text-green-700 bg-green-50',
                    'Ditolak'             => 'border-gray-400 text-gray-500 bg-gray-50',
                    'Dibatalkan'          => 'border-gray-400 text-gray-500 bg-gray-50',
                ];
                $order->statusClass = $colors[$order->status] ?? 'border-gray-300 text-gray-500 bg-gray-50';
                $order->formatted_date = $order->created_at->translatedFormat('d M Y');
                if($order->created_at->isToday()) $order->formatted_date = 'Hari Ini';
                elseif($order->created_at->isYesterday()) $order->formatted_date = 'Kemarin';
                
                return $order;
            });

        // Stats for History Tab
        $totalTransaction = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Selesai')->sum('total_price');
        $monthlyTransaction = ResellerOrder::where('reseller_id', $user->id)->where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        
        // Real bonus calculation
        $pendingBonus = $user->bonus_balance ?? 0;

        // Bonus logs (simplified)
        $bonusLogs = \App\Models\BonusAllocation::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get()
            ->map(function($log) {
                return [
                    'nama' => $log->description,
                    'ket' => 'Alokasi bonus sistem',
                    'nominal' => '+Rp ' . number_format($log->amount, 0, ',', '.'),
                    'tgl' => $log->created_at->translatedFormat('d M Y'),
                    'type' => $log->type // target or referral
                ];
            });

        return view('dashboard.reseller.history', compact('orders', 'totalTransaction', 'monthlyTransaction', 'pendingBonus', 'bonusLogs'));
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
            // Potong stok fisik distributor saat pesanan dinyatakan selesai
            $distributor = $order->distributor;
            if ($distributor->stock < $order->quantity) {
                return back()->with('error', 'Gagal konfirmasi: Stok distributor tidak mencukupi untuk menyelesaikan transaksi ini.');
            }
            
            $distributor->decrement('stock', $order->quantity);
            
            $order->update(['status' => 'Selesai']);
            return back()->with('success', 'Pesanan telah dikonfirmasi selesai. Stok gudang distributor telah diperbarui secara otomatis.');
        }

        return back()->with('error', 'Pesanan tidak dalam status dikirim.');
    }

    public function referrals()
    {
        $user = Auth::user();
        $referrals = User::where('upline_id', $user->id)->where('role', 'reseller')->get();
        
        $totalReferrals = $referrals->count();
        
        // Real referrals from DB
        $referrals = User::where('upline_id', $user->id)
            ->where('role', 'reseller')
            ->get()
            ->map(function($ref) {
                return [
                    'nama' => $ref->name,
                    'bergabung' => $ref->created_at->translatedFormat('M Y'),
                    'status' => $ref->status === 'verified' ? 'Aktif' : 'Menunggu',
                    'aktif' => $ref->status === 'verified'
                ];
            });

        // Personal sales volume (MTD)
        $personalSales = ResellerOrder::where('reseller_id', $user->id)
            ->where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        // Referral sales volume (MTD) - recursive logic or direct child
        $referralIds = User::where('upline_id', $user->id)->pluck('id');
        $referralSales = ResellerOrder::whereIn('reseller_id', $referralIds)
            ->where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        // Bonus calculations (example logic)
        $personalBonus = $personalSales * 1000; // Rp 1.000 per PCS
        $referralBonus = $referralSales * 500;  // Rp 500 per PCS override
        $totalToWithdraw = $personalBonus + $referralBonus;

        $stats = [
            'total_referral' => $referrals->count(),
            'aktif_referral' => $referrals->where('aktif', true)->count(),
            'total_komisi' => 'Rp ' . number_format($totalToWithdraw / 1000, 1) . 'jt', // Simplified for UI
        ];

        return view('dashboard.reseller.referrals', compact(
            'referrals', 
            'personalSales', 
            'referralSales', 
            'personalBonus', 
            'referralBonus', 
            'totalToWithdraw',
            'stats'
        ));
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
