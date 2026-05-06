<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\User;
use App\Models\DistributorOrder;

$admin = User::where('username', 'admin')->first();
$distributor = User::where('role', 'distributor')->first();

if ($admin && $distributor) {
    $count = DistributorOrder::where('user_id', $admin->id)->update(['user_id' => $distributor->id]);
    echo "Sukses: Memindahkan $count pesanan dari Admin ke Distributor ($distributor->name).\n";
} else {
    echo "Gagal: Admin atau Distributor tidak ditemukan.\n";
}
