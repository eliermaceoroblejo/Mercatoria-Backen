<?php

namespace App\Http\Controllers;

use App\Models\Bussiness;
use App\Models\Movement;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function getByType(Request $request)
    {
        $movements = Movement::with('client', 'account')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('movement_type_id', $request->movement_type_id)->get();

        foreach ($movements as $movement) {
            if ($movement->client) {
                $movement->client_code = $movement->client->code;
                $movement->client_name = $movement->client->name;
                unset($movement->client);
            }
            if ($movement->account) {
                $movement->account_number = $movement->account->number;
                $movement->account_name = $movement->account->name;
                unset($movement->account);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $movements
        ]);
    }

    public function getById(Request $request)
    {
        $movement = Movement::with('client', 'account', 'user', 'store')
            ->where('bussiness_id', $request->bussiness_id)
            ->where('store_id', $request->store_id)
            ->where('movement_type_id', $request->movement_type_id)
            ->where('id', $request->id)->first();
        if (!$movement) {
            return response()->json([
                'status' => false,
                'message' => 'El movimiento con id: ' . $request->id . ' no existe',
            ]);
        }

        $movement->user_name = $movement->user->name;
        unset($movement->user);

        $movement->store_name = $movement->store->name;
        unset($movement->store);

        if ($movement->client) {
            $movement->client_code = $movement->client->code;
            $movement->client_name = $movement->client->name;
            unset($movement->client);
        }
        if ($movement->account) {
            $movement->account_number = $movement->account->number;
            $movement->account_name = $movement->account->name;
            unset($movement->account);
        }

        $movement->movement_details = MovementDetailsController::getById($request->id, $request->bussiness_id, $request->store_id);


        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $movement
        ]);
    }
}
