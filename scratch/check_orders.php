<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\DistributorOrder;

foreach(DistributorOrder::all() as $o) {
    echo "Order {$o->id}: " . $o->user->name . " (Prov: " . $o->user->province_id . ") Status: {$o->status}\n";
}
