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
                    'title' => $user->role === 'distributor' ? "{$user->name} mendaftar" : "Pendaftaran Reseller: {$user->name}",
                    'subtitle' => $user->role === 'distributor' ? "Distributor Baru — " . ($user->province_name ?? 'N/A') : "Reseller Baru — " . ($user->city_name ?? 'N/A'),
                    'time' => $user->created_at->diffForHumans(),
                ];
            });

        // Fetch recent transactions from both distributor and reseller orders
        $distributorOrders = \App\Models\DistributorOrder::with('user')->latest()->take(5)->get()->map(function($order) {
            return [
                'time' => $order->created_at->diffForHumans(),
                'name' => $order->user->name,
                'type' => 'Distributor',
                'qty' => $order->quantity,
                'total' => 'Rp ' . number_format($order->quantity * 13000, 0, ',', '.'),
                'status' => strtoupper($order->status),
                'created_at' => $order->created_at
            ];
        });

        $resellerOrders = \App\Models\ResellerOrder::with('reseller')->latest()->take(5)->get()->map(function($order) {
            return [
                'time' => $order->created_at->diffForHumans(),
                'name' => $order->reseller->name ?? 'N/A',
                'type' => 'Reseller',
                'qty' => $order->quantity,
                'total' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                'status' => strtoupper($order->status),
                'created_at' => $order->created_at
            ];
        });

        $recentTransactions = $distributorOrders->concat($resellerOrders)->sortByDesc('created_at')->take(5);

        return view('dashboard.admin', compact(
            'distributorsCount',
            'resellersCount',
            'pendingVerificationsCount',
            'provincesCount',
            'recentActivity',
            'recentTransactions'
        ));
    }

    public function mapping()
    {
        $distributors = User::where('role', 'distributor')
            ->with(['resellers' => function($query) {
                $query->orderBy('name');
            }])
            ->get();

        return view('dashboard.admin.mapping', compact('distributors'));
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
        
        $bonusAllocations = \App\Models\BonusAllocation::with('user')->latest()->get();

        return view('dashboard.admin.bonus', compact('targetQty', 'targetReward', 'bonusAllocations'));
    }

    public function distributorOrders()
    {
        $orders = \App\Models\DistributorOrder::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                // Mapping status to colors for the view
                $colors = [
                    'Menunggu Proses' => 'bg-red-100 text-red-800 border-red-300',
                    'Diproses'        => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'Dikirim'         => 'bg-blue-100 text-blue-800 border-blue-300',
                    'Selesai'         => 'bg-green-100 text-green-800 border-green-300',
                ];
                $order->statusColor = $colors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                return $order;
            });

        return view('dashboard.admin.distributor-orders', compact('orders'));
    }

    public function sales()
    {
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
        $totalOmzet = $totalVolume * 13000; // Fallback price
        $totalTransactions = \App\Models\DistributorOrder::count();
        
        $recentTransactions = \App\Models\DistributorOrder::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                $order->invoice_number = $order->order_number;
                $order->total_price = $order->quantity * 13000;
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
            'tracking_number' => 'nullable|string'
        ]);

        $order = \App\Models\DistributorOrder::findOrFail($request->order_id);
        
        // If status changed to Selesai, add stock to distributor
        if ($request->status === 'Selesai' && $order->status !== 'Selesai') {
            $distributor = $order->user;
            $distributor->increment('stock', $order->quantity);
        }

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number
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

    public function requests()
    {
        return view('dashboard.admin.requests');
    }
}
