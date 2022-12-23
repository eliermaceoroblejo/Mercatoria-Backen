<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function getAll(Request $request)
    {
        $stores = Store::where('bussiness_id', $request->bussiness_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $stores
        ]);
    }

    public function getById(Request $request)
    {
        $store = Store::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'El almacén con id: ' .  $request->id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $store
        ]);
    }

    public function addStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bussiness_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);
        $store = Store::where('bussiness_id', $request->bussiness_id)
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bussiness_id' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }
        
        $slug = Str::slug($request->name);
        $store = Store::where('bussiness_id', $request->bussiness_id)
            ->where('slug', $slug)
            ->whereNot('id', $request->id)->first();
        if ($store) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un almacén con ese nombre'
            ]);
        }

        $store = Store::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'El almacén con id: ' . $request->id . ' no existe'
            ]);
        }
        
        $store->name = $request->name;
        $store->slug = $slug;
        $store->update();

        return response()->json([
            'status' => true,
            'message' => 'Almacén editado',
            'data' => $store
        ]);
    }

    public function deleteStore(Request $request)
    {
        $store = Store::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'El almacén con id: ' . $request->id . ' no existe'
            ]);
        }

        $storeProducts = StoreProduct::where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->id)->get();
        if ($storeProducts->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Existen productos asociados al amnacén ' . $request->id
            ]);
        }
        $store->delete();

        return response()->json([
            'status' => true,
            'message' => 'Almacén eliminado'
        ]);
    }
}
