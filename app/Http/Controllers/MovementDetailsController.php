<?php

namespace App\Http\Controllers;

use App\Models\MovementDetail;
use App\Models\StoreProduct;
use App\Models\Unit;
use Illuminate\Http\Request;

class MovementDetailsController extends Controller
{
    public static function getById($movement_id, $bussiness_id, $store_id)
    {
        $details = MovementDetail::with('product')
            ->where('movement_id', $movement_id)->get();

        foreach ($details as $detail) {
            $detail->product_code = $detail->product->code;
            $detail->product_name = $detail->product->name;

            $unit = Unit::where('bussiness_id', $bussiness_id)
                ->where('id', $detail->product->unit_id)->first();

            $detail->product_unit = $unit->abbreviation;

            $product_store = StoreProduct::where('bussiness_id', $bussiness_id)
                ->where('store_id', $store_id)
                ->where('product_id', $detail->product_id)->first();

            $detail->product_sale_price = $product_store ? $product_store->sale_price : 0;

            unset($detail->product);
        }

        return $details;
    }
}
