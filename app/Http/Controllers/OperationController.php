<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\OperationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Js;
use PHPUnit\Framework\Constraint\Operator;

class OperationController extends Controller
{
    public function getAll(Request $request)
    {
        $operations = Operation::where('bussines_id', $request->bussiness_id)->get();
        return response()->json([
            'status', true,
            'message' => 'OK',
            'data' => $operations
        ]);
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
        $validator = Validator::make($request->all(), [
            'modules_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'bussiness_id' => 'required|numeric',
            'total_debit' => 'required|numeric',
            'total_credit' => 'required|numeric',
        ]);

        if (validator()->fails()) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $operation = Operation::create([
            'module_id' => $request->module_id,
            'user_id' => $request->user_id,
            'bussines_id' => $request->bussiness_id,
            'total_debit' => floatval($request->total_debit),
            'total_credit' => floatval($request->total_credit),
        ]);

        $operation->save();

        foreach ($request->details as $detail) {
            $details = OperationDetail::create([
                'operation_id' => $operation->id,
                'account_id' => $detail->account_id,
                'module_id' => $detail->module_id,
                'reference' => $detail->reference,
                'debit' => floatval($detail->debit),
                'credit' => floatval($detail->credit),
            ]);
            $details->save();
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Operación creada correctamente',
            'data' => $operation
        ]);
    }
}
