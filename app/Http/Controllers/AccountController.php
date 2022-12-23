<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use App\Models\AccountType;
use App\Models\Balance;
use App\Models\Client;
use App\Models\ClientOperations;
use App\Models\OperationDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;

class AccountController extends Controller
{
    public function getAccounts(Request $request)
    {
        $accounts = Account::with(['currency', 'accountType', 'accountGroup', 'accountNature'])
            ->where('bussiness_id', $request->bussiness_id)
            ->orderBy('number')->get();

        foreach ($accounts as $account) {
            $account->currency_id = $account->currency->id;
            $account->currency_name = $account->currency->name;
            $account->account_type_name = $account->accountType->name;
            $account->account_group_name = $account->accountGroup->name;
            $account->account_nature_name = $account->accountNature->name;
            unset($account->currency);
            unset($account->accountType);
            unset($account->accountGroup);
            unset($account->accountNature);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ],);
    }

    public function getAccountByNature(Request $request)
    {
        $accounts = Account::where('bussiness_id', $request->bussiness_id)
            ->where('accountNature', $request->nature);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ]);
    }

    public function getAccountByCurrency(Request $request)
    {
        $accounts = Account::where('bussiness_id', $request->bussiness_id)
            ->where('currency_id', $request->currency_id);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ],);
    }

    public function addAccount(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'number' => 'required|numeric|max:999',
                'account_nature_id' => 'required|numeric|min:1',
                'currency_id' => 'required|numeric|min:1',
                'account_type' => 'required|numeric|min:1',
                'account_group_id' => 'required|numeric|min:1',
                'name' => 'required|string|max:255',
                'bussiness_id' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('number', $request->number)->first();
            if ($account) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Ya existe la cuenta'
                ]);
            }

            $account = Account::create([
                'number' => $request->number,
                'name' => $request->name,
                'account_nature_id' => $request->account_nature_id,
                'currency_id' => $request->currency_id,
                'account_type' => $request->account_group_id,
                'account_group_id' => $request->account_type,
                'bussiness_id' => $request->bussiness_id,
            ]);

            $account->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cuenta añadida',
                'data' => $account
            ], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function editAccount(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'account_nature_id' => 'required|numeric',
                'currency_id' => 'required|numeric',
                'account_type' => 'required|numeric',
                'account_group_id' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('number', $request->number)->first();
            if (!$account) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta no encontrada'
                ], 400);
            }

            // Verificar si tiene saldo y se está cambiando la moneda o el tipo de cuenta
            $balance = Balance::where('bussiness_id', $request->bussiness_id)
                ->where('account_id', $request->id)->get();

            if (($account->account_nature_id != $request->accont_nature_id ||
                    $account->currency_id != $request->currency_id) &&
                $balance->count() > 0
            ) {
                DB::rollBack();
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

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Cuenta modificada',
                'data' => $account
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function deleteAccount(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->id)->first();

            if (!$account) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta no encontrada'
                ], 400);
            }

            $balance = Balance::where('bussiness_id', $request->bussiness_id)
                ->where('account_id', $request->id)->get();
            if ($balance->count() > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta con saldo, no se puede eliminar'
                ], 400);
            }


            $accountOperations = OperationDetail::where('account_id', $request->id)->get();

            if ($accountOperations->count() > 0) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Cuenta con movimientos en el año, no se puede eliminar'
                ], 400);
            }

            $account->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'La cuenta ' . $account->id . ' (' . $account->name . ') ha sido eliminada',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function findAccountById(Request $request)
    {
        $account = Account::where('bussiness_id', $request->bussiness_id)->where('id', $request->id)->first();
        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta ' . $request->id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $account
        ]);
    }

    public function findAccountByNumber(Request $request)
    {
        $account = Account::where('bussiness_id', $request->bussiness_id)->where('number', $request->number)->first();
        if (!$account) {
            return response()->json([
                'status' => false,
                'message' => 'La cuenta ' . $request->id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $account
        ]);
    }

    public function lockAccount(Request $request)
    {
        DB::beginTransaction();
        try {
            $account = Account::where('bussiness_id', $request->bussiness->id);
            if (!$account) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'La cuenta que intenta modificar no existe'
                ]);
            }
            $account->locked = $request->locked;
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'OK',
                'data' => $account
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public static function verifyLockedAccount($bussiness_id, $id)
    {
        $account = Account::where('bussiness_id', $bussiness_id)
            ->where('id', $id)->first();
        if (!$account) {
            throw new Exception("No existe la cuenta con id: " . $id);
        }
        if ($account->locked) {
            throw new Exception("La cuenta " . $account->number . " está bloqueada, no se puede revertir la operación");
        }
    }

    public function getAllAccountClientOperations(Request $request)
    {
        $arr = DB::select('SELECT a.number  as accountNumber, a.name as accountName, c.code as clientCode, 
            c.name as clientName, SUM(movement) AS Importe 
            FROM client_operations AS co
            INNER JOIN accounts AS a ON a.id = co.account_id
            INNER JOIN clients AS C ON c.id = co.client_id
            WHERE co.bussiness_id = ?
            GROUP BY co.bussiness_id, c.code, c.name, a.number, a.name
            ORDER BY a.number', [$request->bussiness_id]);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $arr
        ]);
    }

    public function getAccountByType(Request $request)
    {
        $accountType = AccountGroup::whereIn('code', $request->code)->get();
        $types = [];
        foreach ($accountType as $type) {
            array_push($types, $type->id);
        }
        $accounts = Account::with('accountGroup')
            ->where('bussiness_id', $request->bussiness_id)
            ->whereIn('account_group_id', $types)->get();


        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $accounts
        ]);
    }
}
