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
                'phone' => '08123456789'
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
                'province_id' => '32', // Jawa Barat
                'stock' => 1000
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
                'province_id' => '32',
                'city_id' => '3273', // Bandung
                'upline_id' => User::where('role', 'distributor')->first()->id ?? null
            ]
        );

        // Default Pricing
        Pricing::updateOrCreate(['tier' => 'distributor'], ['price' => 1250000]);
        Pricing::updateOrCreate(['tier' => 'reseller'], ['price' => 1450000]);

        // Default Settings
        Setting::updateOrCreate(['key' => 'monthly_target_qty'], ['value' => '1000']);
        Setting::updateOrCreate(['key' => 'monthly_target_reward'], ['value' => '2500000']);

    }
}
