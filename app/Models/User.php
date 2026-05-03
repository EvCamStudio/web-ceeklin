<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function resellers()
    {
        return $this->hasMany(User::class, 'upline_id');
    }

    public function upline()
    {
        return $this->belongsTo(User::class, 'upline_id');
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
