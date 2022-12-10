<?php

namespace App\Http\Controllers;

use App\Models\OperationDetail;
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

    public static function addOperationDetail($bussiness_id, $module_id, $detail, $operation_id)
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
            ClientOperationsController::addClientOperation($bussiness_id, $detail);
        }
    }
}
