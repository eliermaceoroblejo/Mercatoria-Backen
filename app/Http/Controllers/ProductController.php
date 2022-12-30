<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StoreAccounts;
use App\Models\StoreProduct;
use App\Models\Unit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function getByCode(Request $request)
    {
        $product = Product::with('unit')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('code', $request->code)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'El producto con código: ' . $request->code . ' no existe'
            ]);
        }
        $product->UM = $product->unit->abbreviation;
        $product->UM_unitary = $product->unit->unitary;
        unset($product->unit);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $product
        ]);
    }

    public function getByCodeByStore(Request $request)
    {
        $product = Product::with('unit')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('code', $request->code)
            ->store('store_id', $request->store_id)->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'El producto con código: ' . $request->code . ' no existe en el almacén con id: ' . $request->store_id
            ]);
        }

        $product->UM = $product->unit->abbreviation;
        $product->UM_unitary = $product->unit->unitary;
        unset($product->unit);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $product
        ]);
    }

    public function addProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|numeric',
                'unit_id' => 'required|numeric|min:1',
                'bussiness_id' => 'required|numeric',
                'name' => 'required|string|max:255',
                'sale_price' => 'required|numeric'
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
            if (!$um) {
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
                'bussiness_id' => $request->bussiness_id,
                'sale_price' => $request->sale_price
            ]);

            if ($request->inventory_account > 0) {
                $storeAccount = StoreAccounts::where('bussiness_id', $request->bussiness_id)
                    ->where('id', $request->inventory_account)->first();
                StoreProduct::create([
                    'store_id' => $request->store_id,
                    'product_id' => $product->id,
                    'account_id' => $storeAccount->account_id,
                    'amount' => 0,
                    'price' => 0,
                    'total' => 0,
                    'bussiness_id' => $request->bussiness_id
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }

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
            'name' => 'required|string|max:255',
            'sale_price' => 'required|numeric'
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
        if (!$um) {
            return response()->json([
                'status' => false,
                'message' => 'La unidad de medida que intenta asociar al producto no existe para este negocio'
            ]);
        }

        $product->name = $request->name;
        $product->unit_id = $request->unit_id;
        $product->sale_price = $request->sale_price;
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
