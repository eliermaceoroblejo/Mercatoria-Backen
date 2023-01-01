<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\ClientOperations;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientOperationsController extends Controller
{
    public function getAllClientOperations(Request $request)
    {
        $arr = DB::select('SELECT c.code as clientCode, c.name as clientName, a.number accountNumber, a.name as accountName, SUM(co.movement) AS Movement
            FROM clients AS C
            INNER JOIN client_operations co ON c.id = co.client_id
            INNER JOIN accounts a ON a.id = co.account_id
            WHERE co.bussiness_id = ?
            GROUP BY c.code, c.name, a.number, a.name
            ORDER BY c.code, a.number', [$request->bussiness_id]);

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $arr
        ]);
    }

    public function getByCode(Request $request)
    {
        $clientBalance = ClientOperations::with(['accounts', 'clients'])->where('bussiness_id', $request->bussiness_id)
            ->where('client_id', $request->client_id)->get();

        foreach ($clientBalance as $client) {
            $client->account_id = $client->accounts->id;
            $client->account_name = $client->accounts->name;
            $client->client_id = $client->clients->id;
            $client->client_name = $client->clients->name;
            unset($client->accounts);
            unset($client->clients);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $clientBalance
        ]);
    }

    public function getByAccount(Request $request)
    {
        $clientBalance = ClientOperations::with(['accounts', 'clients'])->where('bussiness_id', $request->bussiness_id)
            ->where('account_id', $request->account_id)->get();

        foreach ($clientBalance as $client) {
            $client->account_id = $client->accounts->id;
            $client->account_name = $client->accounts->name;
            $client->client_id = $client->clients->id;
            $client->client_name = $client->clients->name;
            unset($client->accounts);
            unset($client->clients);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $clientBalance
        ]);
    }

    public static function addClientOperation($bussiness_id, $details, $revert)
    {
        $account = Account::where('bussiness_id', $bussiness_id)
            ->where('id', $details['account_id'])->first();

        if (!$account) {
            throw new Exception('No existe la cuenta ' . $details['account'] . 'en el catálogo');
        }

        $clientOperationReferenceByAccount = ClientOperations::where('bussiness_id', $bussiness_id)
            ->where('account_id', $details['account_id'])
            ->where('client_id', $details['client_id'])
            ->where('reference', $details['reference'])->first();

        if ($clientOperationReferenceByAccount && !$revert) {
            // if ($account->account_group_id == 2 && $details['client_id'] == $clientOperationReferenceByAccount->client_id)
            throw new Exception('Ya existe una operación con la referencia ' . $details['reference'] . ' para el cliente ' . $details['client_id']);
        }

        $movement = 0;
        if ($details['operationNature'] == 1) {
            $movement = $account->account_nature_id == 1 ? floatval($details['amount']) : floatval($details['amount'] * -1);
        } else {
            $movement = $account->account_nature_id == 2 ? floatval($details['amount']) : floatval($details['amount'] * -1);
        }

        ClientOperations::create([
            'client_id' => $details['client_id'],
            'bussiness_id' => $bussiness_id,
            'account_id' => $details['account_id'],
            'movement' => $movement,
            'reference' => $details['reference']
        ]);
    }
}
