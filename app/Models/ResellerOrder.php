<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerOrder extends Model
{
    protected $fillable = [
        'order_number',
        'reseller_id',
        'distributor_id',
        'quantity',
        'total_price',
        'status',
        'payment_status',
        'payment_proof',
    ];

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function distributor()
    {
        return $this->belongsTo(User::class, 'distributor_id');
    }
}
