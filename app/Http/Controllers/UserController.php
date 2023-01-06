<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function setCurrentBussiness(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'status', false,
                'message' => 'El usuario al que intenta modificar el negocio actual no existe'
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

    public function getUserById(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'No existe el usuario con id: ' . $request->user_id
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $user
        ]);
    }
}
