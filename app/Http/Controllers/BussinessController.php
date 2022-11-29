<?php

namespace App\Http\Controllers;

use App\Models\Bussiness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BussinessController extends Controller
{
    public function getAll(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => Bussiness::with('user', $request->user_id)->get()
        ]);
    }

    public function getById(Request $request)
    {
        $bussiness = Bussiness::with('user')->where('user_id', $request->user_id)->first();
        if (!$bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'El negocio que intenta buscar no existe para este usuario',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => Bussiness::with('user', $request->user_id)->get()
        ]);
    }

    public function addBussiness(Request $request)
    {
        $validator = Validator::make([
            'name', 'required|string|max;255',
            'user_id', 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        // if ($request->hasFile("avatar")) {
        //     $avatar = $request->file('avatar');
        //     $avatar_name = Str::slug($request->name) . "." . $avatar->guessExtension();
        //     $avatar_route = public_path("avatar/bussiness/");
        //     copy($avatar->getRealPath(), $avatar_route . $avatar_name);
        //     $avatar_image = $avatar_name;
        // }

        $bussiness = Bussiness::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            // 'avatar' => $avatar_image
        ]);

        $bussiness->save();
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $bussiness
        ]);
    }
}
