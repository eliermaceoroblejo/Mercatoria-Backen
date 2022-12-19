<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoreProduct;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getAll(Request $request)
    {
        $products = Product::with('unit')
            ->where('bussiness_id', $request->bussiness_id)->get();

        foreach ($products as $product) {
            $product->UM = $product->unit->abbreviation;
            unset($product->unit);
        }
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $products
        ]);
    }

    public function getById(Request $request)
    {
        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'El producto con id: ' . $request->id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $product
        ]);
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'bussiness_id' => 'required|numeric',
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);

        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('slug', $slug)->first();

        if ($product) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un producto con ese nombre'
            ]);
        }

        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('code', $request->code)->first();

        if ($product) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un producto con ese código'
            ]);
        }

        $um = Unit::where('bussiness_id', $request->bussiness_id) 
            ->where('id', $request->unit_id)->first();
        if(!$um) {
            return response()->json([
                'status' => false,
                'message' => 'La unidad de medida que intenta asociar al producto no existe para este negocio'
            ]);
        }
        
        $product = Product::create([
            'code' => $request->code,
            'name' => $request->name,
            'slug' => $slug,
            'unit_id' => $request->unit_id,
            'bussiness_id' => $request->bussiness_id
        ]);
        
        $product->UM = $um->abbreviation;

        return response()->json([
            'status' => true,
            'message' => 'Producto agregado',
            'data' => $product
        ]);
    }

    public function editProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
            'unit_id' => 'required|numeric',
            'bussiness_id' => 'required|numeric',
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);

        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('slug', $slug)
            ->whereNot('id', $request->id)->first();

        if ($product) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un producto con ese nombre'
            ]);
        }

        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'No existe el producto con id: ' . $request->id
            ]);
        }

        if ($product->unit_id != $request->unit_id) {
            $productStoreStock = StoreProduct::where('bussiness_id', $request->bussiness_id)
                ->where('product_id', $request->id)->get();

            if ($productStoreStock->count() > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'El producto que desea modificar ya tiene movimientos, no puede modificar la unidad de medida'
                ]);
            }
        }

        $um = Unit::where('bussiness_id', $request->bussiness_id) 
            ->where('id', $request->unit_id)->first();
        if(!$um) {
            return response()->json([
                'status' => false,
                'message' => 'La unidad de medida que intenta asociar al producto no existe para este negocio'
            ]);
        }

        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->slug = $slug;

        $product->save();

        $product->UM = $um->abbreviation;

        return response()->json([
            'status' => true,
            'message' => 'Producto modificado',
            'data' => $product
        ]);
    }

    public function deleteProduct(Request $request)
    {
        $product = Product::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'El producto con id: ' . $request->id . ' no existe'
            ]);
        }

        $productStoreStock = StoreProduct::where('bussiness_id', $request->bussiness_id)
            ->where('product_id', $request->id)->get();

        if ($productStoreStock->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'El producto que desea eliminar ya tiene movimientos, no se puede eliminar'
            ]);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Producto eliminado',
        ]);
    }
}
