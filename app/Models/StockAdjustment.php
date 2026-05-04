<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = ['user_id', 'system_stock', 'physical_stock', 'reason', 'status', 'admin_note'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
