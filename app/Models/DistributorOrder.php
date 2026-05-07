<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $order_number
 * @property int $user_id
 * @property int $quantity
 * @property float $price
 * @property float $total_price
 * @property string $status
 * @property string|null $tracking_number
 * @property string|null $courier_name
 * @property string|null $notes
 * @property string|null $evidence_photo
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */
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
        'courier_name',
        'notes',
        'evidence_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
