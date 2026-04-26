<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Master Admin',
            'username' => 'admin',
            'email' => 'admin@ceeklin.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'ktp_photo' => 'ktp_photos/admin_default.jpg',
            'address' => 'Kantor Pusat Ceeklin',
            'province_id' => '32', // Jawa Barat
            'city_id' => '3273',  // Kota Bandung
            'district_id' => '3273100', // Coblong
            'bank_name' => 'BCA',
            'bank_account_name' => 'ADMIN CEEKLIN',
            'bank_account_number' => '123456789',
        ]);
    }
}
