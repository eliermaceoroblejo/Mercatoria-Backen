<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function changePassword(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No se encuentra el usuario con id: ' . $request->user_id
            ], 400);
        }

        $loginParams = [
            'email' => $user->email,
            'password' => $request->current_password
        ];
        if (!Auth::attempt($loginParams)) {
            return response()->json([
                'status' => false,
                'message' => 'La contraseña actual no coinciden'
            ], 401);
        }

        if ($request->new_password != $request->new_password2) {
            return response()->json([
                'status' => false,
                'message' => 'No coinciden las contraseñas nuevas'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña actualizada',
            'data' => $user
        ]);
    }
}
