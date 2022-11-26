<?php

namespace App\Http\Controllers;

use App\Models\AccountNature;
use Illuminate\Http\Request;

class AccountNatureController extends Controller
{
    public function getAccountNature(Request $request) {
        return response()->json([
            'status' => true,
            'data' => AccountNature::all()
        ]);
    }
}
