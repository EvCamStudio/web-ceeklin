<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $status
 * @property string $phone
 * @property string $nik
 * @property string $ktp_photo
 * @property string $address
 * @property string|null $province_id
 * @property string|null $city_id
 * @property string|null $district_id
 * @property string|null $bank_name
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property string|null $referral_code
 * @property int|null $upline_id
 * @property string|null $reject_reason
 * @property int $stock
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $upline
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ResellerOrder[] $resellerOrders
 */
#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'role',
    'status',
    'phone',
    'nik',
    'ktp_photo',
    'address',
    'province_id',
    'city_id',
    'district_id',
    'bank_name',
    'bank_account_name',
    'bank_account_number',
    'referral_code',
    'upline_id',
    'reject_reason',
    'stock'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProvinceNameAttribute()
    {
        return \Laravolt\Indonesia\Models\Province::where('code', $this->province_id)->value('name') ?? $this->province_id;
    }

    public function getCityNameAttribute()
    {
        return \Laravolt\Indonesia\Models\City::where('code', $this->city_id)->value('name') ?? $this->city_id;
    }

    public function province()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_id', 'code');
    }

    public function city()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_id', 'code');
    }

    public function resellers()
    {
        return $this->hasMany(User::class, 'upline_id');
    }

    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_id');
    }

    public function resellerOrders()
    {
        return $this->hasMany(ResellerOrder::class, 'reseller_id');
    }

    protected static function booted()
    {
        static::creating(function ($user) {
            if ($user->role === 'reseller' && empty($user->referral_code)) {
                $user->referral_code = 'CK-' . strtoupper(bin2hex(random_bytes(3)));
            }
        });
    }
}
