<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    public function checkNik(Request $request)
    {
        $exists = User::where('nik', $request->nik)->exists();
        return response()->json(['available' => !$exists]);
    }

    public function checkUsername(Request $request)
    {
        $exists = User::where('username', $request->username)->exists();
        return response()->json(['available' => !$exists]);
    }

    public function checkBankAccount(Request $request)
    {
        $exists = User::where('bank_account_number', $request->account)->exists();
        return response()->json(['available' => !$exists]);
    }
}
