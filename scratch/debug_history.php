<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\DistributorOrder;
use App\Models\ResellerOrder;

// Ambil user distributor pertama untuk testing (atau sesuaikan ID-nya)
$user = User::where('role', 'distributor')->first();

if (!$user) {
    echo "USER DISTRIBUTOR TIDAK DITEMUKAN\n";
    exit;
}

echo "DEBUG UNTUK USER: {$user->name} (ID: {$user->id})\n";

$purchases = DistributorOrder::where('user_id', $user->id)->count();
echo "JUMLAH PEMBELIAN (RESTOCK): {$purchases}\n";

$sales = ResellerOrder::where('distributor_id', $user->id)->where('status', 'Selesai')->count();
echo "JUMLAH PENJUALAN (KE RESELLER): {$sales}\n";

// Cek status mentah di DB
$rawPurchases = DistributorOrder::where('user_id', $user->id)->pluck('status')->unique();
echo "STATUS PEMBELIAN DI DB: " . implode(', ', $rawPurchases->toArray()) . "\n";

$rawSales = ResellerOrder::where('distributor_id', $user->id)->pluck('status')->unique();
echo "STATUS PENJUALAN DI DB: " . implode(', ', $rawSales->toArray()) . "\n";
