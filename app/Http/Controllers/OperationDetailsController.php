<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\OperationDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationDetailsController extends Controller
{
    public function getByAccount(Request $request)
    {
        $detail = OperationDetail::with('accounts')->where('bussiness_id', $request->bussiness_id)
            ->where('account_id', $request->account_id)->get();

        if ($detail->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Los detalles de la operación no existen'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $detail
        ]);
    }

    public static function addOperationDetail($bussiness_id, $module_id, $detail, $operation_id, $revert)
    {
        OperationDetail::create([
            'operation_id' => $operation_id,
            'account_id' => $detail['account_id'],
            'module_id' => $module_id,
            'reference' => $detail['reference'],
            'client' => $detail['client'],
            'debit' => $detail["operationNature"] == 1 ? floatval($detail['amount']) : floatval(0),
            'credit' => $detail["operationNature"] == 2 ? floatval($detail['amount']) : floatval(0),
        ]);
        BalanceController::updatetBalance($bussiness_id, $detail);
        if ($detail['client']) {
            ClientOperationsController::addClientOperation($bussiness_id, $detail, $revert);
        }
    }

    public static function getOperationDetail($operation_id)
    {
        $operationDetail = OperationDetail::with('account')->where('operation_id', $operation_id)->get();
        if (!$operationDetail) {
            throw new Exception("La operación con id: " . $operation_id . " no existe");
        }
        return $operationDetail;
    }

    public function getOperationDetail1(Request $request)
    {
        $operationDetail = OperationDetail::with('account')->where('operation_id', $request->operation_id)->get();
        if (!$operationDetail) {
            return response()->json([
                'status' => false,
                'message' => "La operación con id: " . $request->operation_id . " no existe"
            ]);
        }
        foreach ($operationDetail as $detail) {
            if ($detail->client) {
                $client = Client::where('bussiness_id', $request->bussiness_id)
                    ->where('code', $detail->client)->first();
                if (!$client) {
                    return response()->json([
                        'status' => false,
                        'message' => 'El cliente ' . $detail->client . ' no existe'
                    ]);
                }
                $detail->client_id = $client->id;
            }
            $detail->account_number = $detail->account->number;
            unset($detail->account);
        }
        return response()->json([
            'status' => true,
            'message' => "OK",
            'data' => $operationDetail
        ]);
    }
}
