<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function getAll(Request $request)
    {
        $stores = Store::where('bussiness_id', $request->bussinness_id)->get();

        return response()->json([
            'status' => false,
            'message' => 'OK',
            'data' => $stores
        ]);
    }

    public function addStore(Request $request)
    {
        $validator = Validator::make([
            'name' => 'required|string|max:255',
            'bussiness_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);
        $store = Store::where('bussiness_id', $request->bussinness_id)
            ->where('slug', $slug)->first();
        if ($store) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un almacén con ese nombre'
            ]);
        }
        $store = Store::create([
            'name' => $request->name,
            'bussiness_id' => $request->bussiness_id,
            'slug' => $slug
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Almacén creado',
            'data' => $store
        ]);
    }

    public function editStore(Request $request)
    {
        $store = Store::where('bussiness_id', $request->bussiness)
            ->where('id', $request->id)->first();
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'El almacén con id: ' . $request->id . ' no existe'
            ]);
        }
        $store->name = $request->name;
        $store->slug = Str::slug($request->name);

        $store->save();

        return response()->json([
            'status' => true,
            'message' => 'Almacén editado',
            'data' => $store
        ]);
    }
}
