<?php

namespace App\Http\Controllers;

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
}
