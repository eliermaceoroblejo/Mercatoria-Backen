<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\MovementDetail;
use App\Models\Product;
use App\Models\StoreProduct;
use App\Models\Unit;
use Exception;
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

            $detail->product_sale_price = $detail->product->sale_price;

            unset($detail->product);
        }

        return $details;
    }

    public static function addMovementDetail($bussiness_id, $store_id, $movement_id, $movement_type_id, $movementDetails)
    {
        foreach ($movementDetails as $detail) {

            $product = Product::where('bussiness_id', $bussiness_id)
                ->where('id', $detail['product_id'])->first();
            if (!$product) {
                throw new Exception('El producto con código: ' . $detail['product_code'] . ' no existe');
            }

            $productStore = StoreProduct::where('bussiness_id', $bussiness_id)
                ->where('store_id', $store_id)
                ->where('product_id', $detail['product_id'])->first();
            if (!$productStore) {
                throw new Exception('El producto con código: ' . $detail['product_code'] . ' no existe para el almacén con id: ' . $store_id);
            }

            $account = Account::where('bussiness_id', $bussiness_id)
                ->where('id', $productStore->account_id)->first();
            if (!$account) {
                throw new Exception('La cuenta con id: ' . $productStore->account_id . ' no existe');
            }

            if (
                $movement_type_id == 2 &&
                $productStore->amount - $detail['product_quantity'] < 0
            ) {
                throw new Exception('El producto ' . $detail['product_code'] . ' no tiene existencia disponible');
            }

            MovementDetail::create([
                'movement_id' => $movement_id,
                'movement_type_id' => $movement_type_id,
                'product_id' => $detail['product_id'],
                'account_id' => $account->id,
                'quantity' => $detail['product_quantity'],
                'price' => $detail['product_price'],
                'total' => $detail['product_import']
            ]);

            $productStore->amount = $movement_type_id == 1
                ? $productStore->amount + $detail['product_quantity']
                : $productStore->amount - $detail['product_quantity'];
            $productStore->total = $movement_type_id == 1
                ? $productStore->total + $detail['product_import']
                : $productStore->total - $detail['product_import'];
            if ($movement_type_id == 1) {
                $productStore->price = round($productStore->total / $productStore->amount, 7);
            }
            $productStore->update();
        }
    }
}
