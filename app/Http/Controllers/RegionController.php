<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class RegionController extends Controller
{
    public function getProvinces()
    {
        // Menggunakan 'code' sebagai value dropdown agar bisa dicari di tabel Kota
        return Province::orderBy('name')->get(['code as id', 'name']);
    }

    public function getCities(Request $request)
    {
        $provinceCode = $request->province_id; // Ini sekarang berisi 'code' dari provinsi
        if (!$provinceCode) {
            return response()->json([]);
        }
        
        return City::where('province_code', $provinceCode)
            ->orderBy('name')
            ->get(['code as id', 'name']);
    }

    public function getDistricts(Request $request)
    {
        $cityCode = $request->city_id; // Ini sekarang berisi 'code' dari kota
        if (!$cityCode) {
            return response()->json([]);
        }

        return District::where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['code as id', 'name']);
    }
}
