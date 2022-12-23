<?php

namespace App\Http\Controllers;

use App\Models\StoreAccounts;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreAccountsController extends Controller
{
    public function getByStore(Request $request) {
        $storeAccounts = StoreAccounts::with('account')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)->get();

        foreach ($storeAccounts as $store_account) {
            $store_account->account_number = $store_account->account->number;
            $store_account->account_name = $store_account->account->name;
            unset($store_account->account);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $storeAccounts
        ]);
    }

    public function addStoreAccount(Request $request) {

        $validator = Validator::make($request->all(), [
            'bussiness_id' => 'required|numeric',
            'store_id' => 'required|numeric',
            'account_id' => 'required|numeric|min:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $storeAccount = StoreAccounts::where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('account_id', $request->account_id)->first();

        if($storeAccount) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta que intenta agregar ya existe para este almacén'
            ]);
        }

        $storeAccount = StoreAccounts::create([
            'bussiness_id' => $request->bussiness_id,
            'store_id' => $request->store_id,
            'account_id' => $request->account_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cuenta agregada al almacén',
            'data' => $storeAccount
        ]);
    }

    public function deleteStoreAccount(Request $request) {
        $storeAccount = StoreAccounts::where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('account_id', $request->account_id)->first();

        if(!$storeAccount) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la cuenta que desea eliminar para este almacén'
            ]);
        }

        $storeProducts = StoreProduct::where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('account_id', $request->account_id)->get();
        if($storeProducts->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta que desea eliminar tiene productos asociados. No se puede eliminar'
            ]);
        }

        $storeAccount->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cuenta eliminada del almacén'
        ]);
    }
}
