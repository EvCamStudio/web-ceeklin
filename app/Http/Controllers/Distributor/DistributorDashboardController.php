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
        
        $recentOrders = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.distributor', compact('user', 'resellersCount', 'pendingOrdersCount', 'recentOrders'));
    }

    public function inventory()
    {
        $user = Auth::user();
        // Assuming we only have one product for now
        $stockPercentage = ($user->stock / 5000) * 100;
        
        // Dynamic stock history (simplified)
        $incomingLogs = DistributorOrder::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->select('order_number', 'quantity', 'created_at')
            ->get()
            ->map(fn($log) => [
                'type' => 'in', 
                'desc' => "Restock Pabrik - {$log->order_number}", 
                'qty' => "+{$log->quantity}", 
                'date' => $log->created_at->format('d M Y'), 
                'color' => 'text-green-600', 
                'timestamp' => $log->created_at
            ]);

        $outgoingLogs = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(fn($log) => ['type' => 'out', 'desc' => "Pesanan Reseller - {$log->reseller->name}", 'qty' => "-{$log->quantity}", 'date' => $log->created_at->format('d M Y'), 'color' => 'text-red-600', 'timestamp' => $log->created_at]);

        $logs = $incomingLogs->concat($outgoingLogs)->sortByDesc('timestamp')->take(10);

        return view('dashboard.distributor.inventory', compact('user', 'stockPercentage', 'logs'));
    }

    public function resellers()
    {
        $resellers = User::where('upline_id', Auth::id())
            ->orderBy('name')
            ->get();
        return view('dashboard.distributor.resellers', compact('resellers'));
    }

    public function incomingOrders()
    {
        $orders = ResellerOrder::with('reseller')
            ->where('distributor_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($order) {
                $colors = [
                    'Menunggu Konfirmasi' => 'bg-red-100 text-red-800 border-red-300',
                    'Diproses'            => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'Dikirim'             => 'bg-blue-100 text-blue-800 border-blue-300',
                    'Selesai'             => 'bg-green-100 text-green-800 border-green-300',
                ];
                $order->statusColor = $colors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                return $order;
            });
        return view('dashboard.distributor.incoming-orders', compact('orders'));
    }

    public function order()
    {
        // This is for distributor ordering from factory
        $orders = DistributorOrder::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('dashboard.distributor.order', compact('orders'));
    }

    public function salesMap()
    {
        $user = Auth::user();
        
        // Get reseller counts by city for resellers under this distributor
        $cityStats = User::where('upline_id', $user->id)
            ->join('indonesia_cities', 'users.city_id', '=', 'indonesia_cities.code')
            ->select('indonesia_cities.name as city_name', \DB::raw('count(*) as count'))
            ->groupBy('indonesia_cities.name')
            ->get();

        return view('dashboard.distributor.sales-map', compact('cityStats'));
    }

    public function history()
    {
        $user = Auth::user();
        
        // Purchase History (from Factory/Admin)
        $purchases = DistributorOrder::where('user_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->order_number,
                    'type' => 'Pembelian',
                    'qty' => $order->quantity,
                    'total' => $order->total_price,
                    'status' => $order->status,
                    'date' => $order->updated_at,
                    'color' => 'border-blue-500 text-blue-700',
                    'leftBorder' => 'border-blue-500'
                ];
            });

        // Sales History (to Resellers)
        $sales = ResellerOrder::with('reseller')
            ->where('distributor_id', $user->id)
            ->where('status', 'Selesai')
            ->get()
            ->map(function($order) {
                return (object)[
                    'id' => $order->order_number,
                    'type' => 'Penjualan',
                    'qty' => $order->quantity,
                    'total' => $order->total_price,
                    'status' => $order->status,
                    'date' => $order->updated_at,
                    'color' => 'border-green-600 text-green-700',
                    'leftBorder' => 'border-green-600'
                ];
            });

        $history = $purchases->concat($sales)->sortByDesc('date');

        return view('dashboard.distributor.history', compact('history'));
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
            'status' => 'required'
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
        }

        $order->update(['status' => $request->status]);

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
        $request->validate([
            'qty' => 'required|integer|min:1000'
        ]);

        $price = 13000; // Harga modal distributor
        $total = $request->qty * $price;

        DistributorOrder::create([
            'user_id' => Auth::id(),
            'order_number' => 'RE-' . strtoupper(uniqid()),
            'quantity' => $request->qty,
            'total_price' => $total,
            'status' => 'Menunggu Konfirmasi'
        ]);

        return back()->with('success', 'Pesanan restock berhasil dikirim ke pabrik.');
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
}
