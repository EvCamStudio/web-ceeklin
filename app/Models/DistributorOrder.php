<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributorOrder extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'quantity',
        'price',
        'total_price',
        'status',
        'tracking_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
