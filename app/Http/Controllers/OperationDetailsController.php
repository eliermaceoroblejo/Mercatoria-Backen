<?php

namespace App\Http\Controllers;

use App\Models\OperationDetail;
use Illuminate\Http\Request;

class OperationDetailsController extends Controller
{
    public function getByAccount(Request $request)
    {
        $detail = OperationDetail::where('bussiness_id', $request->bussiness_id)
            ->where('operation_id', $request->operation_id)->get();

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
}
