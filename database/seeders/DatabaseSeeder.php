<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Data Wilayah Indonesia (Pro Way)
        $this->command->info('Sedang mengisi data wilayah Indonesia... (Sabar ya, ini agak lama)');
        Artisan::call('laravolt:indonesia:seed');
        $this->command->info('Data wilayah berhasil diisi!');

        // 2. Seed User Admin
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Master Admin',
                'email' => 'admin@ceeklin.test',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '081234567890',
                'nik' => '1234567890123456',
                'ktp_photo' => 'ktp_photos/admin_default.jpg',
                'address' => 'Kantor Pusat Ceeklin',
                'province_id' => '12', // Sumatera Utara
                'city_id' => '1271',  // Kota Medan
                'bank_name' => 'BCA',
                'bank_account_name' => 'ADMIN CEEKLIN',
                'bank_account_number' => '123456789',
            ]
        );
        $this->command->info('User Admin berhasil dibuat!');

        // 3. Seed Admin Panel Data (Distributor, Reseller, Pricing, Settings)
        $this->call(AdminPanelSeeder::class);
        $this->command->info('Data Panel Admin (Distributor & Reseller) berhasil diisi!');
    }
}
