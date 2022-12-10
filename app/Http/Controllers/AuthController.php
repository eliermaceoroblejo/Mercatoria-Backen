<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'auth_token' => $token,
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $user = User::whereEmail(strtolower($request->email))->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        if (!$user->current_bussiness) {
            $user->current_bussiness = 0;
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Datos incorrectos'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => true,
            'data' => $user,
            'auth_token' => $token
        ]);
    }

    public function forgot_password(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }

        $pin = rand(1000, 9000);
        $user->pin = $pin;
        $user->pin_expired_in = now()->addHours(24);
        $user->update();

        // Send pin via Email

        return response()->json([
            'status' => true,
            'message' => 'Pin sended'
        ]);
    }

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'pin' => 'required|numeric',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
        $expire = Carbon::parse($user->pin_expired_in)->diffInHours(now());
        if ($expire > 24) {
            return response()->json([
                'status' => false,
                'message' => 'Pin expirado'
            ]);
        }
        if ($user->pin != $request->pin) {
            return response()->json([
                'status' => false,
                'message' => 'Pin inválido'
            ]);
        }

        $user->pin = null;
        $user->pin_expired_in = null;
        $user->password = Hash::make($request->password);

        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'Contraseña cambiada'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Sesión cerrada'
        ]);
    }
}
