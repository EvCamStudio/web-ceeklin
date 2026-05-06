<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pricing;
use App\Models\Setting;
use App\Models\DistributorOrder;
use App\Models\SalesReport;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test Accounts
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Master Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'phone' => '08123456789',
                'province_id' => '12', // Sumatera Utara
                'city_id' => '1271', // Medan
            ]
        );

        User::updateOrCreate(
            ['username' => 'distributor'],
            [
                'name' => 'PT Distributor Sukses',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'distributor',
                'status' => 'active',
                'phone' => '081222333444',
                'nik' => '1100223344556677',
                'ktp_photo' => 'ktp_photos/default.jpg',
                'address' => 'Jl. Meulaboh No. 1, Aceh',
                'province_id' => '11', // Aceh
                'city_id' => '1105',   // Aceh Barat
                'district_id' => '1105010',
                'bank_name' => 'BCA',
                'bank_account_name' => 'PT DISTRIBUTOR SUKSES',
                'bank_account_number' => '888000111',
                'stock' => 0
            ]
        );

        User::updateOrCreate(
            ['username' => 'reseller'],
            [
                'name' => 'Toko Reseller Berkah',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'reseller',
                'status' => 'active',
                'phone' => '081333444555',
                'nik' => '3200112233445566',
                'ktp_photo' => 'ktp_photos/default_r.jpg',
                'address' => 'Jl. Braga No. 10, Bandung',
                'province_id' => '32', // Jawa Barat
                'city_id' => '3273',   // Kota Bandung
                'district_id' => '3273010',
                'bank_name' => 'BCA',
                'bank_account_name' => 'RESELLER BERKAH',
                'bank_account_number' => '777000222',
                'upline_id' => User::where('username', 'distributor')->first()->id ?? null
            ]
        );

        // Default Pricing
        Pricing::updateOrCreate(['tier' => 'distributor'], ['price' => 13000]);
        Pricing::updateOrCreate(['tier' => 'reseller'], ['price' => 15000]);

        // Default Settings
        Setting::updateOrCreate(['key' => 'monthly_target_qty'], ['value' => '1000']);
        Setting::updateOrCreate(['key' => 'monthly_target_reward'], ['value' => '2500000']);

        /*
        // Seed Distributor Orders (Commented out for flow testing)
        $distributor = User::where('username', 'distributor')->first();
        if ($distributor) {
            DistributorOrder::updateOrCreate(
                ['order_number' => 'ORD-20240501-001'],
                [
                    'user_id' => $distributor->id,
                    'quantity' => 100,
                    'price' => 13000,
                    'total_price' => 1300000,
                    'status' => 'Menunggu Proses',
                ]
            );

            DistributorOrder::updateOrCreate(
                ['order_number' => 'ORD-20240502-002'],
                [
                    'user_id' => $distributor->id,
                    'quantity' => 250,
                    'price' => 13000,
                    'total_price' => 3250000,
                    'status' => 'Diproses',
                ]
            );

            DistributorOrder::updateOrCreate(
                ['order_number' => 'ORD-20240503-003'],
                [
                    'user_id' => $distributor->id,
                    'quantity' => 500,
                    'price' => 13000,
                    'total_price' => 6500000,
                    'status' => 'Dikirim',
                    'tracking_number' => 'JKT12345678',
                    'courier_name' => 'Kurir Pabrik'
                ]
            );
        }
        */
    }
}
