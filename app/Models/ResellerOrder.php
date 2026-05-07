<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $order_number
 * @property int $reseller_id
 * @property int $distributor_id
 * @property int $quantity
 * @property float $total_price
 * @property string $status
 * @property string $payment_status
 * @property string|null $payment_proof
 * @property string|null $courier_name
 * @property string|null $tracking_number
 * @property string|null $reject_reason
 * @property string|null $note
 * @property string|null $evidence_photo
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */
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
        'courier_name',
        'tracking_number',
        'reject_reason',
        'note',
        'evidence_photo',
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
