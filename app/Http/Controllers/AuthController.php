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

        //TODO: Send pin via Email

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
                'message' => 'Pin inv??lido'
            ]);
        }

        $user->pin = null;
        $user->pin_expired_in = null;
        $user->password = Hash::make($request->password);

        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'Contrase??a cambiada'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Sesi??n cerrada'
        ]);
    }

    public function changePassword(Request $request)
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

        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No se encuentra el usuario con id: ' . $request->user_id
            ], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Datos incorrectos'
            ], 401);
        }

        if ($request->new_password != $request->new_password2) {
            return response()->json([
                'status' => false,
                'message' => 'No coinciden las contrase??as nuevas'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'Contrase??a actualizada',
            'data' => $user
        ]);
    }
}
