<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Balance;
use App\Models\OperationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function getAccounts(Request $request)
    {
        $accounts = Account::with(['currency', 'accountType', 'accountGroup', 'accountNature'])->orderBy('id', 'asc')->get();

        foreach ($accounts as $account) {
            $account->currency_id = $account->currency->id;
            $account->currency_name = $account->currency->name;
            $account->account_type_name = $account->accountType->name;
            $account->account_group_name = $account->accountGroup->name;
            $account->account_nature_name = $account->accountNature->name;
            unset($account->currency);
            unset($account->accountType);
            unset($account->accountGroup);
        }


        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ],);
    }

    public function getAccountByNature(Request $request, $nature)
    {
        $accounts = Account::with('accountNature', $nature);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ]);
    }

    public function getAccountByCurrency(Request $request, $currency)
    {
        $accounts = Account::with('currency', $currency);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ],);
    }

    public function addAccount(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|max:999',
            'account_nature_id' => 'required|numeric',
            'currency_id' => 'required|numeric',
            'account_type' => 'required|numeric',
            'account_group_id' => 'required|numeric',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $account = Account::whereId($request->id)->first();
        if ($account) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe la cuenta'
            ]);
        }

        $account = Account::create([
            'id' => $request->id,
            'name' => $request->name,
            'account_nature_id' => $request->account_nature_id,
            'currency_id' => $request->currency_id,
            'account_type' => $request->account_group_id,
            'account_group_id' => $request->account_type
        ]);

        $account->save();

        return response()->json([
            'status' => true,
            'message' => 'Cuenta añadida',
            'data' => $account
        ], 201);
    }

    public function editAccount(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'account_nature_id' => 'required|numeric',
            'currency_id' => 'required|numeric',
            'account_type' => 'required|numeric',
            'account_group_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $account = Account::whereId($id)->first();
        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Cuenta no encontrada'
            ], 400);
        }

        // Verificar si tiene saldo y se está cambiando la moneda o el tipo de cuenta
        $balance = Balance::where('account_id', $id)->get();


        if (($account->account_nature_id != $request->accont_nature_id ||
                $account->currency_id != $request->currency_id) &&
            $balance->count() > 0
        ) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta tiene saldo, no se puede editar'
            ], 400);
        }

        $account->name = $request->name;
        $account->account_nature_id = $request->account_nature_id;
        $account->currency_id = $request->currency_id;
        $account->account_type = $request->account_group_id;
        $account->account_group_id = $request->account_type;

        $account->update();

        return response()->json([
            'status' => true,
            'message' => 'Cuenta modificada',
            'data' => $account
        ]);
    }

    public function deleteAccount(Request $request, $id)
    {
        $account = Account::where('id', $id)->first();

        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'Cuenta no encontrada'
            ], 400);
        }

        $balance = Balance::where('account_id', $id)->get();
        if ($balance->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cuenta con saldo, no se puede eliminar'
            ], 400);
        }


        $accountOperations = OperationDetail::where('account_id', $id)->get();

        if ($accountOperations->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cuenta con movimientos en el año, no se puede eliminar'
            ], 400);
        }

        $account->delete();

        return response()->json([
            'status' => true,
            'message' => 'La cuenta ' . $account->id . ' (' . $account->name . ') ha sido eliminada',
        ]);
    }

    public function findAccount(Request $request, $id)
    {
        $account = Account::where('id', $id)->first();
        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta ' . $id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $account
        ]);
    }
}
