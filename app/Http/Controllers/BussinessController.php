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
        $bussinesses = Bussiness::where('user_id', $request->user_id)->get();
        foreach ($bussinesses as $bussiness) {
            unset($bussiness->user_id);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $bussinesses
        ]);
    }

    public function getById(Request $request)
    {
        $bussiness = Bussiness::where('user_id', $request->user_id)
            ->where('id', $request->id)->first();
        if (!$bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'El negocio que intenta buscar no existe para este usuario',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $bussiness
        ]);
    }

    public function addBussiness(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);
        $bussiness = Bussiness::where('slug', $slug)->first();
        if ($bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'El negocio que desea agregar ya existe'
            ]);
        }

        $bussiness = Bussiness::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'avatar' => null
            // 'avatar' => $avatar_image
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Negocio creado',
            'data' => $bussiness
        ]);
    }

    public function editBussiness(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'user_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);
        $bussiness = Bussiness::where('slug', $slug)->first();
        if ($bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un negocio con este nombre'
            ]);
        }
        
        $bussiness = Bussiness::where('id', $request->id)->where('user_id', $request->user_id)->first();
        if (!$bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'El negocio que intenta modificar no existe'
            ]);
        }

        $bussiness->name = $request->name;
        $bussiness->avatar = $request->avatar;
        $bussiness->update();

        return response()->json([
            'status' => true,
            'message' => 'Negocio actualizado',
            'data' => $bussiness
        ]);
    }

    public function deleteBussiness(Request $request)
    {
        $bussiness = Bussiness::where('user_id', $request->user_id)
            ->where('id', $request->id);
        if (!$bussiness) {
            return response()->json([
                'status' => false,
                'message' => 'No existe el negocio que desea eliminar'
            ]);
        }

        $bussiness->delete();

        return response()->json([
            'status' => true,
            'message' => 'Negocio eliminado'
        ]);
    }
}
