<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\EditCurrencyRequest;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\all;

class CurrenciesController extends Controller
{
    public function getCurrencies(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => Currency::where('bussiness_id', $request->bussiness_id)->orderBy('id', 'asc')->get()
        ]);
    }

    public function getCurrencyById(Request $request)
    {
        $currency = Currency::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$currency) {
            return response()->json([
                'status' => true,
                'message' => 'La moneda que intenta buscar no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $currency
        ]);
    }

    public function addCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:5',
            'rate' => 'required|numeric',
            'bussiness_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $currency = Currency::where('bussiness_id', $request->bussiness_id)
            ->where('name', $request->name)
            ->where('abbreviation', $request->abbreviation)->first();
        if ($currency) {
            return response()->json([
                'status' => false,
                'message' => 'Revise el nombre o la abreviatura de la moneda que está intentado agregar, 
                    ya existen registros con esos datos'
            ], 400);
        }

        $currency = Currency::create([
            'name' => $request->name,
            'abbreviation' => strtoupper($request->abbreviation),
            'rate' => $request->rate,
            'bussiness_id' => $request->bussiness_id
        ]);

        $currency->save();

        return response()->json([
            'status' => true,
            'data' => $currency
        ]);
    }

    public function editCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:5',
            'rate' => 'required|numeric',
            'bussiness_id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $currency = Currency::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$currency) {
            return response()->json([
                'status' => false,
                'message' => 'La moneda no existe'
            ]);
        }

        $currency->name = $request->name;
        $currency->abbreviation = strtoupper($request->abbreviation);
        $currency->rate = $request->rate;

        $currency->update();

        return response()->json([
            'status' => true,
            'data' => $currency
        ]);
    }

    public function deleteCurrency(Request $request)
    {
        $currency = Currency::where('bussiness_id', $request->bussiness_id)
            ->whereId($request->id)->first();
        if (!$currency) {
            return response()->json([
                'status' => false,
                'message' => 'La moneda que intenta eliminar no existe'
            ]);
        }

        $account = Account::where('bussiness_id', $request->bussiness_id)
            ->where('currency_id', $request->id)->first();

        if ($account) {
            return response()->json([
                'status' => false,
                'message' => 'La moneda está asociada a al menos una cuenta, no es posible eliminarla'
            ]);
        }

        $currency->delete();

        return response()->json([
            'status' => true,
            'message' => 'La moneda ha sido eliminada'
        ]);
    }
}
