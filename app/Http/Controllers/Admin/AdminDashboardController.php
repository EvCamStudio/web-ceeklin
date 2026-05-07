<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function overview()
    {
        $distributorsCount = User::where('role', 'distributor')->count();
        $resellersCount = User::where('role', 'reseller')->where('status', 'active')->count();
        $pendingVerificationsCount = User::where('role', 'reseller')->where('status', 'pending')->count();
        $pendingDistributorOrdersCount = \App\Models\DistributorOrder::where('status', 'Menunggu Proses')->count();
        $pendingBonusRequestsCount = \App\Models\BonusAllocation::where('status', 'pending')->count();
        
        $distributorPrice = \App\Models\Pricing::where('tier', 'distributor')->value('price') ?? 13000;
        
        // Provinces with active distributors
        $provincesCount = User::where('role', 'distributor')
            ->whereNotNull('province_id')
            ->distinct('province_id')
            ->count();

        $recentActivity = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'icon' => $user->role === 'distributor' ? '📦' : '🛡️',
                    'msg' => $user->role === 'distributor' ? "Distributor baru: {$user->name} mendaftar" : "Reseller baru: {$user->name} menunggu verifikasi",
                    'time' => $user->created_at->diffForHumans(),
                ];
            });

        // Fetch recent transactions from distributor orders
        $recentTransactions = \App\Models\DistributorOrder::with('user')->latest()->take(7)->get()->map(function($order) use ($distributorPrice) {
            return [
                'time' => $order->created_at->diffForHumans(),
                'name' => $order->user->name,
                'type' => 'Distributor',
                'qty' => $order->quantity,
                'total' => 'Rp ' . number_format($order->quantity * $distributorPrice, 0, ',', '.'),
                'status' => strtoupper($order->status),
                'created_at' => $order->created_at
            ];
        });

        // Top Performers (based on volume)
        $topPerformers = \App\Models\DistributorOrder::where('status', 'Selesai')
            ->with('user')
            ->select('user_id', DB::raw('SUM(quantity) as total_volume'))
            ->groupBy('user_id')
            ->orderBy('total_volume', 'desc')
            ->take(3)
            ->get();

        $totalCentralStock = User::where('role', 'distributor')->sum('stock');
        
        // Dynamic stock percentage based on a target capacity (e.g., 50,000 bottles)
        $nationalCapacity = \App\Models\Setting::where('key', 'national_capacity')->value('value') ?? 50000;
        $stockPercentage = min(100, round(($totalCentralStock / $nationalCapacity) * 100));

        return view('dashboard.admin', compact(
            'distributorsCount',
            'resellersCount',
            'pendingVerificationsCount',
            'pendingDistributorOrdersCount',
            'pendingBonusRequestsCount',
            'provincesCount',
            'recentActivity',
            'recentTransactions',
            'topPerformers',
            'totalCentralStock',
            'stockPercentage'
        ));
    }

    public function mapping()
    {
        $allResellers = User::where('role', 'reseller')
            ->with(['upline', 'city'])
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'city' => $user->city_name ?? 'N/A',
                    'city_id' => $user->city_id,
                    'old_dist' => $user->upline->name ?? 'Pusat',
                    'dist_city' => $user->upline->city_name ?? 'N/A',
                    'stock' => $user->upline->stock ?? 0,
                ];
            });

        $priorityResellers = $allResellers->filter(fn($r) => $r['stock'] <= 0 && $r['old_dist'] !== 'Pusat');
        
        $optimizeResellers = $allResellers->filter(function($r) {
            // Logic: Current distributor is NOT in the same city as reseller, 
            // BUT there is another distributor in the same city as reseller.
            $hasLocalDistributor = User::where('role', 'distributor')
                ->where('city_id', $r['city_id'])
                ->exists();
            
            return $r['city'] !== $r['dist_city'] && $hasLocalDistributor;
        });

        $distributors = User::where('role', 'distributor')
            ->with('city')
            ->get()
            ->map(function($d) {
                return [
                    'id' => $d->id,
                    'name' => $d->name,
                    'city' => $d->city_name ?? 'N/A',
                    'city_id' => $d->city_id,
                    'stock' => number_format($d->stock)
                ];
            });

        return view('dashboard.admin.mapping', compact('allResellers', 'priorityResellers', 'optimizeResellers', 'distributors'));
    }

    public function pricing()
    {
        $distributorPrice = \App\Models\Pricing::where('tier', 'distributor')->latest()->first();
        $resellerPrice = \App\Models\Pricing::where('tier', 'reseller')->latest()->first();

        return view('dashboard.admin.pricing', compact('distributorPrice', 'resellerPrice'));
    }

    public function bonus()
    {
        $targetQty = \App\Models\Setting::where('key', 'monthly_target_qty')->value('value') ?? 1000;
        $targetReward = \App\Models\Setting::where('key', 'monthly_target_reward')->value('value') ?? 2500000;

        // --- RETROACTIVE BONUS CHECK ---
        // Find resellers who reached target this month but don't have a bonus record yet
        $achievers = \App\Models\ResellerOrder::where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select('reseller_id', DB::raw('SUM(quantity) as total_sales'))
            ->groupBy('reseller_id')
            ->having('total_sales', '>=', $targetQty)
            ->get();

        foreach ($achievers as $achiever) {
            $alreadyAwarded = \App\Models\BonusAllocation::where('user_id', $achiever->reseller_id)
                ->where('quarter', now()->format('F Y'))
                ->exists();

            if (!$alreadyAwarded) {
                \App\Models\BonusAllocation::create([
                    'user_id' => $achiever->reseller_id,
                    'amount' => $targetReward,
                    'status' => 'pending',
                    'quarter' => now()->format('F Y')
                ]);
            }
        }
        // -------------------------------
        
        $bonusRequests = \App\Models\BonusAllocation::where('status', 'pending')
            ->with(['user', 'user.upline'])
            ->latest()
            ->get();

        // Reseller Leaderboard (Monthly)
        $leaderboard = \App\Models\ResellerOrder::where('status', 'Selesai')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->with(['reseller', 'reseller.upline'])
            ->select('reseller_id', DB::raw('SUM(quantity) as total_sales'))
            ->groupBy('reseller_id')
            ->orderBy('total_sales', 'desc')
            ->get()
            ->map(function($item) use ($targetQty, $targetReward) {
                $item->progress = min(100, round(($item->total_sales / $targetQty) * 100));
                // Potential bonus is the full target reward if they are on track
                $item->potential = 'Rp ' . number_format($targetReward, 0, ',', '.');
                return $item;
            });

        return view('dashboard.admin.bonus', compact('targetQty', 'targetReward', 'bonusRequests', 'leaderboard'));
    }

    public function distributorOrders()
    {
        $distributorPrice = \App\Models\Pricing::where('tier', 'distributor')->value('price') ?? 13000;

        $orders = \App\Models\DistributorOrder::with('user')->latest()->get()->map(function($order) use ($distributorPrice) {
            return [
                'id' => $order->order_number ?? ('ORD-' . $order->id),
                'db_id' => $order->id,
                'name' => $order->user->name,
                'phone' => $order->user->phone,
                'city' => ($order->user->city_name ?? 'N/A') . ', ' . ($order->user->province_name ?? ''),
                'qty' => $order->quantity,
                'status' => $order->status,
                'date' => $order->created_at->translatedFormat('d M Y, H:i'),
                'items' => 'CeeKlin 450ml (x' . number_format($order->quantity) . ')',
                'total' => 'Rp ' . number_format($order->quantity * $distributorPrice, 0, ',', '.'),
                'method' => $order->payment_method ?? 'Manual Transfer',
                'note' => $order->notes ?? '',
                'evidence_photo' => $order->evidence_photo,
                'courier_name' => $order->courier_name ?? '',
                'tracking_number' => $order->tracking_number ?? ''
            ];
        });

        $stats = [
            'Menunggu' => $orders->where('status', 'Menunggu Proses')->count(),
            'Dikemas' => $orders->where('status', 'Diproses')->count(),
            'Dikirim' => $orders->where('status', 'Dikirim')->count(),
            'Masalah' => $orders->where('status', 'Masalah')->count(),
        ];

        return view('dashboard.admin.distributor-orders', compact('orders', 'stats'));
    }

    public function sales()
    {
        $distributorPrice = \App\Models\Pricing::where('tier', 'distributor')->value('price') ?? 13000;

        // 1. National Trend: Sum volume by month from completed orders
        $monthlyTrend = \App\Models\DistributorOrder::where('status', 'Selesai')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                DB::raw('SUM(quantity) as volume')
            )
            ->groupBy('period')
            ->orderBy('period', 'asc')
            ->get();

        // 2. Regional Breakdown: Sum volume by province from completed orders
        $regionalBreakdown = \App\Models\DistributorOrder::where('distributor_orders.status', 'Selesai')
            ->join('users', 'users.id', '=', 'distributor_orders.user_id')
            ->select(
                'users.province_id',
                DB::raw('SUM(distributor_orders.quantity) as volume')
            )
            ->groupBy('users.province_id')
            ->orderBy('volume', 'desc')
            ->get()
            ->map(function($item) {
                // Attach province name
                $item->region = \Laravolt\Indonesia\Models\Province::where('code', $item->province_id)->value('name') ?? $item->province_id;
                return $item;
            });

        // Summary Stats
        $totalVolume = \App\Models\DistributorOrder::where('status', 'Selesai')->sum('quantity');
        $totalOmzet = $totalVolume * $distributorPrice;
        $totalTransactions = \App\Models\DistributorOrder::count();
        
        $recentTransactions = \App\Models\DistributorOrder::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) use ($distributorPrice) {
                $order->invoice_number = $order->order_number;
                $order->total_price = $order->quantity * $distributorPrice;
                return $order;
            });

        // Simple growth calculation
        $lastMonthVolume = \App\Models\DistributorOrder::where('status', 'Selesai')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('quantity');
        $growth = $lastMonthVolume > 0 ? (($totalVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

        return view('dashboard.admin.sales', compact(
            'monthlyTrend', 
            'regionalBreakdown',
            'totalVolume',
            'totalOmzet',
            'totalTransactions',
            'recentTransactions',
            'growth'
        ));
    }

    public function settings()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('dashboard.admin.settings', compact('settings'));
    }

    public function updateDistributorOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:distributor_orders,id',
            'status' => 'required',
            'tracking_number' => 'required_if:status,Dikirim',
            'note' => 'nullable|string'
        ]);

        $order = \App\Models\DistributorOrder::findOrFail($request->order_id);
        
        // Logical Guard: Prevent modification of Finished or Cancelled orders
        if (in_array($order->status, ['Selesai', 'Dibatalkan', 'Ditolak'])) {
            return back()->with('error', 'Pesanan yang sudah selesai atau dibatalkan tidak dapat diubah lagi statusnya.');
        }

        // If status changed to Selesai, add stock to distributor
        if ($request->status === 'Selesai') {
            /** @var User $distributor */
            $distributor = $order->user;
            $distributor->increment('stock', $order->quantity);
        }

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'courier_name' => $request->courier_name,
            'notes' => $request->note
        ]);

        return back()->with('success', 'Status pesanan distributor berhasil diperbarui.');
    }

    public function updatePricing(Request $request)
    {
        $request->validate([
            'distributor_price' => 'required|numeric|min:0',
            'reseller_price' => 'required|numeric|min:0'
        ]);

        \App\Models\Pricing::updateOrCreate(['tier' => 'distributor'], ['price' => $request->distributor_price]);
        \App\Models\Pricing::updateOrCreate(['tier' => 'reseller'], ['price' => $request->reseller_price]);

        return back()->with('success', 'Harga berhasil diperbarui.');
    }

    public function updateBonusSettings(Request $request)
    {
        $request->validate([
            'target_qty' => 'required|integer|min:1',
            'reward_amount' => 'required|numeric|min:0'
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'monthly_target_qty'], ['value' => $request->target_qty]);
        \App\Models\Setting::updateOrCreate(['key' => 'monthly_target_reward'], ['value' => $request->reward_amount]);

        return back()->with('success', 'Pengaturan bonus berhasil diperbarui.');
    }

    public function migrateReseller(Request $request)
    {
        $request->validate([
            'reseller_id' => 'required|exists:users,id',
            'distributor_id' => 'required|exists:users,id'
        ]);

        $reseller = User::where('id', $request->reseller_id)->where('role', 'reseller')->firstOrFail();
        $reseller->update(['upline_id' => $request->distributor_id]);

        return redirect()->route('admin.mapping')->with('success', 'Reseller berhasil dimigrasikan.');
    }

    public function approveBonus(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:bonus_allocations,id'
        ]);

        $bonus = \App\Models\BonusAllocation::findOrFail($request->request_id);
        $bonus->update(['status' => 'paid']);

        return back()->with('success', 'Pencairan bonus berhasil disetujui.');
    }

    public function rejectBonus(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:bonus_allocations,id'
        ]);

        $bonus = \App\Models\BonusAllocation::findOrFail($request->request_id);
        $bonus->update(['status' => 'rejected']);

        return back()->with('success', 'Pengajuan bonus telah ditangguhkan.');
    }

    public function requests()
    {
        $adjustments = \App\Models\StockAdjustment::with(['user', 'user.city'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('dashboard.admin.requests', compact('adjustments'));
    }

    public function approveAdjustment(Request $request)
    {
        $request->validate(['request_id' => 'required|exists:stock_adjustments,id']);
        
        $adjustment = \App\Models\StockAdjustment::findOrFail($request->request_id);
        
        if ($adjustment->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        // Update Stock
        $user = $adjustment->user;
        $user->stock = $adjustment->physical_stock;
        $user->save();

        $adjustment->update(['status' => 'approved']);

        return back()->with('success', 'Sinkronisasi stok berhasil disetujui dan diperbarui.');
    }

    public function rejectAdjustment(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:stock_adjustments,id',
            'note' => 'nullable|string'
        ]);

        $adjustment = \App\Models\StockAdjustment::findOrFail($request->request_id);
        $adjustment->update([
            'status' => 'rejected',
            'admin_note' => $request->note
        ]);

        return back()->with('success', 'Pengajuan sinkronisasi telah ditolak.');
    }
}
