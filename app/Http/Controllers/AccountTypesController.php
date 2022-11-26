<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypesController extends Controller
{
    public function getAccountsTypes(Request $request) {
        return response()->json([
            'status' => true,
            'data' => AccountType::all()
        ]);
    }
}
