<?php

namespace App\Http\Controllers\Distributor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ResellerOrder;
use App\Models\DistributorOrder;
use Illuminate\Support\Facades\Auth;

class DistributorDashboardController extends Controller
{
    public function overview()
    {
        $user = Auth::user();
        $resellersCount = User::where('upline_id', $user->id)->count();
        $pendingOrdersCount = ResellerOrder::where('distributor_id', $user->id)
            ->where('status', 'Menunggu Konfirmasi')
            ->count();
        
        // Monthly Sales Volume (MTD)
        $monthlyVolume = ResellerOrder::where('distributor_id', $user->id)
            ->whereIn('status', ['Diproses', 'Dikirim', 'Selesai'])
            ->whereMonth('created_at', now()->month)
            ->sum('quantity');

        // Last Month Volume for comparison
        $lastMonthVolume = ResellerOrder::where('distributor_id', $user->id)
            ->whereIn('status', ['Diproses', 'Dikirim', 'Selesai'])
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('quantity');
        
        $volumeGrowth = $lastMonthVolume > 0 ? (($monthlyVolume - $lastMonthVolume) / $lastMonthVolume) * 100 : 0;

        // Estimated Profit (Margin Rp 2.000 / PCS)
        $estimatedProfit = $monthlyVolume * 2000;

        // Stock Level
        $stockLevel = ($user->stock / 5000) * 100; // Assuming 5000 is capacity
        $stockStatus = $stockLevel > 50 ? 'Aman' : ($stockLevel > 20 ? 'Menengah' : 'Kritis');

        $recentOrders = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.distributor', compact(
            'user', 'resellersCount', 'pendingOrdersCount', 'recentOrders',
            'monthlyVolume', 'volumeGrowth', 'estimatedProfit', 'stockLevel', 'stockStatus'
        ));
    }

    public function inventory()
    {
        $user = Auth::user();
        
        // Stock physical = total in system
        $physicalStock = $user->stock;
        
        // Stock hold = orders paid/processed but not yet finished (if we subtract on finish)
        // Or if we subtract on paid, hold is orders processed but not finished.
        // Actually, let's define hold as orders with status 'Menunggu' and 'Diproses'
        $holdStock = ResellerOrder::where('distributor_id', $user->id)
            ->whereIn('status', ['Menunggu Konfirmasi', 'Menunggu Proses', 'Diproses', 'Dikemas', 'Dikirim', 'Menunggu'])
            ->sum('quantity');
        
        $readyStock = $physicalStock - $holdStock;
        $stockPercentage = ($physicalStock / 5000) * 100; // Capacity 5000 PCS
        
        // Dynamic stock history
        $incomingLogs = DistributorOrder::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(fn($log) => [
                'type' => 'in', 
                'desc' => "Restock Pabrik — {$log->order_number}", 
                'qty' => '+' . number_format($log->quantity), 
                'date' => $log->created_at->translatedFormat('d M Y, H:i'), 
                'color' => 'text-green-600',
                'status' => 'Selesai',
                'note' => '',
                'timestamp' => $log->created_at
            ]);

        $outgoingLogs = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(fn($log) => [
                'type' => 'out', 
                'desc' => "Pesanan Reseller — " . ($log->reseller->name ?? 'User'), 
                'qty' => '-' . number_format($log->quantity), 
                'date' => $log->created_at->translatedFormat('d M Y, H:i'), 
                'color' => 'text-red-600',
                'status' => 'Selesai',
                'note' => '',
                'timestamp' => $log->created_at
            ]);

        // Sync logs from a hypothetical StockAdjustment model or just Filtered Logs
        // For now, let's just use the two main sources
        $allLogs = $incomingLogs->concat($outgoingLogs)->sortByDesc('timestamp');
        $logs = $allLogs->take(15);
        
        // Ambil waktu dari aktivitas terbaru
        $lastUpdate = $allLogs->first() ? $allLogs->first()['timestamp'] : $user->updated_at;

        return view('dashboard.distributor.inventory', compact('user', 'physicalStock', 'holdStock', 'readyStock', 'stockPercentage', 'logs', 'lastUpdate'));
    }

    public function resellers()
    {
        $distributor = Auth::user();
        
        $resellers = User::where('upline_id', $distributor->id)
            ->where('role', 'reseller')
            ->with(['city', 'resellerOrders' => function($q) {
                $q->where('status', 'Selesai')->latest()->limit(1);
            }])
            ->get()
            ->map(function($reseller) {
                $lastOrder = $reseller->resellerOrders->first();
                
                // Detailed stats for the detail view
                $totalSpent = $reseller->resellerOrders()->where('status', 'Selesai')->sum('total_price');
                $totalVolume = $reseller->resellerOrders()->where('status', 'Selesai')->sum('quantity');
                $orderCount = $reseller->resellerOrders()->where('status', 'Selesai')->count();

                // Simple status based on database status
                if ($reseller->status === 'pending') {
                    $resellerStatus = 'Belum Terverifikasi';
                } elseif ($reseller->status === 'active') {
                    $resellerStatus = 'Aktif';
                } else {
                    $resellerStatus = 'Non-Aktif';
                }
                
                $recentTransactions = $reseller->resellerOrders()
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function($order) {
                        return [
                            'id' => $order->order_number,
                            'product' => 'CeeKlin 450ml',
                            'qty' => $order->quantity,
                            'total' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                            'status' => $order->status,
                            'date' => $order->created_at->translatedFormat('d M Y')
                        ];
                    });

                return [
                    'id' => $reseller->id,
                    'name' => $reseller->name,
                    'username' => $reseller->username,
                    'phone' => $reseller->phone,
                    'city' => ($reseller->city_name ?? 'N/A'),
                    'address' => $reseller->address,
                    'joined_at' => $reseller->created_at->translatedFormat('d M Y'),
                    'last_order_date' => $lastOrder ? $lastOrder->created_at->translatedFormat('d M Y') : 'Belum ada',
                    'status' => $resellerStatus,
                    'isPeralihan' => false, 
                    'origin' => 'N/A',
                    'stats' => [
                        'totalSpent' => 'Rp ' . number_format($totalSpent / 1000000, 1) . 'M',
                        'totalSpentRaw' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
                        'totalVolume' => number_format($totalVolume),
                        'orderCount' => $orderCount,
                        'recentTransactions' => $recentTransactions
                    ]
                ];
            });

        $resellerCount = $resellers->count();

        return view('dashboard.distributor.resellers', compact('resellers', 'resellerCount'));
    }

    public function incomingOrders()
    {
        $orders = ResellerOrder::with(['reseller', 'reseller.city', 'reseller.province'])
            ->where('distributor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->order_number,
                    'db_id' => $order->id,
                    'reseller' => $order->reseller->name,
                    'phone' => $order->reseller->phone,
                    'city' => ($order->reseller->city_name ?? 'N/A'),
                    'qty' => $order->quantity,
                    'total' => 'Rp ' . number_format($order->total_price, 0, ',', '.'),
                    'status' => in_array($order->status, ['Menunggu Konfirmasi', 'Menunggu Proses']) ? 'Menunggu' : ($order->status === 'Diproses' ? 'Dikemas' : $order->status),
                    'date' => $order->created_at->translatedFormat('d M Y, H:i'),
                    'items' => 'CeeKlin 450ml (x' . $order->quantity . ')',
                    'is_peralihan' => false, // Placeholder logic
                    'address' => $order->reseller->address ?? 'Alamat tidak tersedia',
                    'raw_status' => $order->status === 'Menunggu Konfirmasi' ? 'Menunggu Proses' : $order->status,
                    'tracking_number' => $order->tracking_number,
                    'courier_name' => $order->courier_name,
                ];
            });

        $stats = [
            'Menunggu' => $orders->where('status', 'Menunggu')->count(),
            'Dikemas' => $orders->where('status', 'Dikemas')->count(),
            'Dikirim' => $orders->where('status', 'Dikirim')->count(),
        ];

        return view('dashboard.distributor.incoming-orders', compact('orders', 'stats'));
    }

    public function order()
    {
        if (Auth::user()->role !== 'distributor') {
            return redirect()->route('dashboard')->with('error', 'Hanya Distributor yang dapat mengakses halaman ini.');
        }

        $productPrice = \App\Models\Pricing::where('tier', 'distributor')->first()->price ?? 13000;
        
        $orders = DistributorOrder::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.distributor.order', compact('orders', 'productPrice'));
    }



    public function history()
    {
        $user = Auth::user();
        
        // Purchase History (from Factory/Admin)
        $purchases = DistributorOrder::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                $statusColors = [
                    'Menunggu Konfirmasi' => 'border-red-400 text-red-700 bg-red-50',
                    'Menunggu Proses'     => 'border-red-400 text-red-700 bg-red-50',
                    'Menunggu'            => 'border-red-400 text-red-700 bg-red-50',
                    'Diproses'            => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                    'Dikemas'             => 'border-yellow-500 text-yellow-800 bg-yellow-50',
                    'Dikirim'             => 'border-blue-500 text-blue-700 bg-blue-50',
                    'Selesai'             => 'border-green-600 text-green-700 bg-green-50',
                    'Dibatalkan'          => 'border-gray-400 text-gray-500 bg-gray-50',
                    'Ditolak'             => 'border-gray-400 text-gray-500 bg-gray-50',
                ];

                $statusLabels = [
                    'Menunggu Konfirmasi' => 'Menunggu',
                    'Menunggu Proses' => 'Menunggu',
                    'Diproses' => 'Dikemas',
                    'Dikirim' => 'Dikirim',
                    'Selesai' => 'Selesai',
                    'Dibatalkan' => 'Dibatalkan',
                    'Ditolak' => 'Dibatalkan',
                ];

                $leftBorders = [
                    'Menunggu Proses' => 'border-gray-500',
                    'Diproses' => 'border-yellow-500',
                    'Dikirim' => 'border-blue-500',
                    'Selesai' => 'border-green-600',
                ];

                return [
                    'id' => (string)($order->order_number ?? 'N/A'),
                    'db_id' => (int)$order->id,
                    'type' => 'Pembelian',
                    'qty' => (int)($order->quantity ?? 0),
                    'total' => 'Rp ' . number_format($order->total_price ?? 0, 0, ',', '.'),
                    'status' => (string)($statusLabels[$order->status] ?? ($order->status ?? 'Menunggu')),
                    'date' => $order->created_at ? $order->created_at->translatedFormat('d M Y') : '-',
                    'statusClass' => (string)($statusColors[$order->status] ?? 'border-gray-500 text-gray-700 bg-gray-50'),
                    'leftBorder' => (string)($leftBorders[$order->status] ?? 'border-gray-500'),
                    'tracking' => (string)($order->tracking_number ?? ''),
                    'courier' => (string)($order->courier_name ?? ''),
                    'notes' => (string)($order->notes ?? ''),
                    'timestamp' => $order->created_at ? $order->created_at->toIso8601String() : now()->toIso8601String()
                ];
            });

        // Sales History (to Resellers)
        $sales = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(function($order) {
                return [
                    'id' => (string)($order->order_number ?? 'N/A'),
                    'db_id' => (int)$order->id,
                    'type' => 'Penjualan',
                    'qty' => (int)($order->quantity ?? 0),
                    'total' => 'Rp ' . number_format($order->total_price ?? 0, 0, ',', '.'),
                    'status' => 'Selesai',
                    'date' => $order->created_at ? $order->created_at->translatedFormat('d M Y') : '-',
                    'statusClass' => 'border-green-600 text-green-700 bg-green-50',
                    'leftBorder' => 'border-green-600',
                    'tracking' => (string)($order->tracking_number ?? ''),
                    'courier' => (string)($order->courier_name ?? ''),
                    'notes' => (string)($order->note ?? ''),
                    'timestamp' => $order->created_at ? $order->created_at->toIso8601String() : now()->toIso8601String()
                ];
            });

        $history = $purchases->concat($sales)->sortByDesc('timestamp')->values();

        return view('dashboard.distributor.history', compact('history'));
    }

    public function confirmReceived(Request $request)
    {
        $request->validate([
            'order_id' => 'required'
        ]);

        $order = DistributorOrder::where('order_number', $request->order_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status === 'Selesai') {
            return back()->with('error', 'Pesanan ini sudah selesai.');
        }

        // Update status and increment stock
        $order->update(['status' => 'Selesai']);
        
        $user = Auth::user();
        $user->increment('stock', $order->quantity);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi. Stok Anda telah diperbarui.');
    }

    public function settings()
    {
        $user = Auth::user();
        return view('dashboard.distributor.settings', compact('user'));
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:reseller_orders,id',
            'status' => 'required',
            'courier_name' => 'nullable|string',
            'tracking_number' => 'required_if:status,Dikirim',
        ]);

        $order = ResellerOrder::where('id', $request->order_id)
            ->where('distributor_id', Auth::id())
            ->firstOrFail();

        $user = Auth::user();

        // Prevent update if already Selesai or Ditolak (to avoid double stock deduction etc)
        if (in_array($order->status, ['Selesai', 'Ditolak'])) {
            return back()->with('error', 'Status pesanan ini tidak dapat diubah lagi.');
        }

        // Logic for stock deduction
        if ($request->status === 'Selesai') {
            if ($user->stock < $order->quantity) {
                return back()->with('error', 'Stok Anda tidak mencukupi untuk menyelesaikan pesanan ini.');
            }
            
            $user->decrement('stock', $order->quantity);

            // Bonus Target Calculation Logic
            $reseller = $order->reseller;
            $targetQty = \App\Models\Setting::where('key', 'monthly_target_qty')->value('value') ?? 1000;
            $targetReward = \App\Models\Setting::where('key', 'monthly_target_reward')->value('value') ?? 2500000;

            // Get total volume for this month (including this order)
            $monthlyVolume = \App\Models\ResellerOrder::where('reseller_id', $reseller->id)
                ->where('status', 'Selesai')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('quantity') + $order->quantity;

            if ($monthlyVolume >= $targetQty) {
                // Check if already awarded this month
                $alreadyAwarded = \App\Models\BonusAllocation::where('user_id', $reseller->id)
                    ->where('quarter', now()->format('F Y'))
                    ->exists();

                if (!$alreadyAwarded) {
                    \App\Models\BonusAllocation::create([
                        'user_id' => $reseller->id,
                        'amount' => $targetReward,
                        'status' => 'pending',
                        'quarter' => now()->format('F Y')
                    ]);
                }
            }
        }

        $updateData = ['status' => $request->status];
        
        if ($request->status === 'Dikirim') {
            $updateData['courier_name'] = $request->courier_name;
            $updateData['tracking_number'] = $request->tracking_number;
        }

        if ($request->filled('note')) {
            $updateData['note'] = $request->note;
        }

        $order->update($updateData);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function cancelOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:reseller_orders,id',
            'cancel_reason' => 'required|string|max:255'
        ]);

        $order = ResellerOrder::where('id', $request->order_id)
            ->where('distributor_id', Auth::id())
            ->firstOrFail();

        $order->update([
            'status' => 'Ditolak',
            'reject_reason' => $request->cancel_reason
        ]);

        return back()->with('success', 'Pesanan telah dibatalkan.');
    }

    public function storeOrder(Request $request)
    {
        if (Auth::user()->role !== 'distributor') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'qty' => 'required|integer|min:1000'
        ]);

        $price = \App\Models\Pricing::where('tier', 'distributor')->first()?->price ?? 13000;
        $total = $request->qty * $price;

        DistributorOrder::create([
            'user_id' => Auth::id(),
            'order_number' => 'RE-' . strtoupper(uniqid()),
            'quantity' => $request->qty,
            'total_price' => $total,
            'status' => 'Menunggu Proses'
        ]);

        return back()->with('order_success', true);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string'
        ]);

        $user->update($request->only('name', 'email', 'phone', 'address'));

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8'
        ]);

        Auth::user()->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function updateBank(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255'
        ]);

        $user->update($request->only('bank_name', 'bank_account_number', 'bank_account_name'));

        return back()->with('success', 'Informasi rekening berhasil diperbarui.');
    }

    public function syncInventory(Request $request)
    {
        $request->validate([
            'stok_fisik' => 'required|integer|min:0',
            'alasan' => 'required|string|max:255'
        ]);

        \App\Models\StockAdjustment::create([
            'user_id' => Auth::id(),
            'system_stock' => Auth::user()->stock,
            'physical_stock' => $request->stok_fisik,
            'reason' => $request->alasan,
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Pengajuan sinkronisasi stok berhasil dikirim ke Admin. Mohon tunggu verifikasi.');
    }
}
