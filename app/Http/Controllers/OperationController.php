<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Operation;
use App\Models\OperationDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use PHPUnit\Framework\Constraint\Operator;

class OperationController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $operations = Operation::with(['module', 'user'])->where('bussiness_id', $request->bussiness_id)->get();
            // ->where('module_id', $request->module_id)->get();
            foreach ($operations as $operation) {
                $operation->module_name = $operation->module->name;
                $operation->username = $operation->user->name;
                unset($operation->module);
                unset($operation->user);
            }
            return response()->json([
                'status' => true,
                'message' => 'OK',
                'data' => $operations
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function getById(Request $request)
    {
        $operation = Operation::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$operation) {
            return response()->json([
                'status' => false,
                'message' => 'No se encuentra la operación que busca'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $operation
        ]);
    }

    public function addOperation(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'module_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'bussiness_id' => 'required|numeric',
                'total_debit' => 'required|numeric',
                'total_credit' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ]);
            }

            $operation = self::createOperation(
                $request->module_id,
                $request->user_id,
                $request->bussiness_id,
                $request->total_debit,
                $request->total_credit,
                $request->details,
                $request->revert
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Comprobante creado correctamente',
                'data' => $operation
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public static function createOperation(
        $module_id,
        $user_id,
        $bussiness_id,
        $total_debit,
        $total_credit,
        $details,
        $revert
    ) {
        try {
            $operation = Operation::create([
                'module_id' => $module_id,
                'user_id' => $user_id,
                'bussiness_id' => $bussiness_id,
                'total_debit' => floatval($total_debit),
                'total_credit' => floatval($total_credit),
            ]);

            foreach ($details as $detail) {
                OperationDetailsController::addOperationDetail(
                    $bussiness_id,
                    $module_id,
                    $detail,
                    $operation->id,
                    $revert
                );
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage());
        }
    }

    public function revertOperation(Request $request)
    {
        $operation = Operation::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$operation) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la operación con id: ' . $request->id
            ]);
        }
        $operationDetail = OperationDetailsController::getOperationDetail($operation->id);

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
            $temp = floatval($detail->debit);
            $detail->debit = floatval($detail->credit);
            $detail->credit = floatval($temp);
            unset($detail->account);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $operationDetail
        ]);
    }
}
