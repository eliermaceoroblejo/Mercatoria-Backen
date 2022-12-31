<?php

namespace App\Http\Controllers;

use App\Models\StoreAccounts;
use App\Models\StoreProduct;
use App\Models\Unit;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    public function getAll(Request $request)
    {
        $products = StoreProduct::with('product', 'account')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)->get();

        foreach ($products as $prod) {
            $prod->product_name = $prod->product->name;
            $prod->product_code = $prod->product->code;

            $unit = Unit::where('bussiness_id', $request->bussiness_id)
                ->where('id', $prod->product->unit_id)->first();
            $prod->product_um = $unit->abbreviation;

            $prod->account_number = $prod->account->number;
            $prod->account_name = $prod->account->name;

            unset($prod->product);
            unset($prod->account);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $products
        ]);
    }

    public function addStoreProduct(Request $request)
    {
        $product = StoreProduct::where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('product_id', $request->product_id)->first();
        if ($product) {
            return response()->json([
                'status' => true,
            ]);
        }

        $storeAccount = StoreAccounts::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->account_id)->first();
        if (!$storeAccount) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la cuenta con id: ' . $request->account_id
            ]);
        }

        $product = StoreProduct::create([
            'bussiness_id' => $request->bussiness_id,
            'store_id' => $request->store_id,
            'account_id' => $storeAccount->account_id,
            'product_id' => $request->product_id,
            'amount' => 0,
            'price' => 0,
            'total' => 0
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Producto agregado',
            'data' => $product
        ]);
    }
}
