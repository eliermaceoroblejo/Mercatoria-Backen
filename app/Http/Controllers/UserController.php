<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function setCurrentBussiness(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'status', false,
                'message' => 'El usuario que intenta modificar no existe'
            ]);
        }
        $user->current_bussiness = $request->bussiness_id;
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $user
        ]);
    }
}
