<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Bussiness;
use App\Models\Client;
use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

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

        $movement->movement_details =
            MovementDetailsController::getById($request->id, $request->bussiness_id, $request->store_id);


        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $movement
        ]);
    }

    public function addMovement(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'movement_type_id' => 'required|numeric|min:1',
                'store_id' => 'required|numeric|min:1',
                'user_id' => 'required|numeric|min:1',
                'client_id' => 'required|numeric|min:1',
                'account_id' => 'required|numeric|min:1',
                'bussiness_id' => 'required|numeric|min:1',
                'reference' => 'required|string|max:255',
                'subtotal' => 'required|numeric',
                'total' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ]);
            }

            $userId = User::where('id', $request->user_id)->first();
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'El usuario con id: ' . $request->user_id . ' no existe'
                ]);
            }

            $bussiness = Bussiness::where('id', $request->bussiness_id)->first();
            if (!$bussiness) {
                return response()->json([
                    'status' => false,
                    'message' => 'El negocio con id: ' . $request->bussiness_id . ' no existe'
                ]);
            }

            if ($bussiness->user_id != $request->user_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'El negocio con id: ' . $request->bussiness_id . ' no está asociado al user con id: ' . $request->user_id
                ]);
            }

            $movementTypeId = MovementType::where('id', $request->movement_type_id)->first();
            if (!$movementTypeId) {
                return response()->json([
                    'status' => false,
                    'message' => 'El tipo de movimiento con id: ' . $request->movement_type_id . ' no existe'
                ]);
            }

            $storeId = Store::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->store_id)->first();
            if (!$storeId) {
                return response()->json([
                    'status' => false,
                    'message' => 'El almacén con id: ' . $request->store_id . ' no existe'
                ]);
            }

            $client = Client::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->client_id)->first();
            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => 'El cliente con id: ' . $request->client_id . ' no existe'
                ]);
            }
            // if ($request->client_id) {
            // }

            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->account_id)->first();
            if (!$account) {
                return response()->json([
                    'status' => false,
                    'message' => 'La cuenta con id: ' . $request->account_id . ' no existe'
                ]);
            }

            $movement = Movement::create([
                'movement_type_id' => $request->movement_type_id,
                'store_id' => $request->store_id,
                'user_id' => $request->user_id,
                'client_id' => $request->client_id,
                'account_id' => $request->account_id,
                'bussiness_id' => $request->bussiness_id,
                'reference' => $request->reference,
                'discount' => $request->discount,
                'overcharge' => $request->overcharge,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'importing_company' => $request->importing_company,
                'financial_expenses' => $request->financial_expenses,
                'transportation' => $request->transportation,
                'manipulation' => $request->manipulation
            ]);

            MovementDetailsController::addMovementDetail(
                $request->bussiness_id,
                $request->store_id,
                $movement->id,
                $request->movement_type_id,
                $request->movement_details
            );

            $total_debit = 0;
            $total_credit = 0;

            $operationDetails = [];
            foreach ($request->movement_details as $detail) {
                $product = StoreProduct::where('bussiness_id', $request->bussiness_id)
                    ->where('store_id', $request->store_id)
                    ->where('product_id', $detail['product_id'])->first();

                if (!$product) {
                    throw new Exception('No existe el producto con id: ' . $detail['product_code'] . ' en el almacén ' . $request->store_id);
                }

                $operationDetail = array(
                    'account_id' => $product->account_id,
                    'reference' => '',
                    'client' => '',
                    'amount' => $detail['product_import'],
                    'operationNature' => $request->movement_type_id == 1 ? 1 : 2
                );

                $total_debit += $request->movement_type_id == 1 ?  $operationDetail['amount'] : 0;
                $total_credit += $request->movement_type_id == 2 ?  $operationDetail['amount'] : 0;
                array_push($operationDetails, $operationDetail);
            }

            // Cuenta de la compra
            $operationDetail = [
                'account_id' => $account->id,
                'reference' => $request->reference,
                'client_id' => $request->client_id,
                'client' => $client->code,
                'amount' => $request->total,
                'operationNature' => $account->account_nature_id == 1 ? 1 : 2
            ];
            $total_debit += $account->account_nature_id == 1 ? $request->total : 0;
            $total_credit += $account->account_nature_id == 2 ? $request->total : 0;
            array_push($operationDetails, $operationDetail);

            OperationController::createOperation(
                3,
                $request->user_id,
                $request->bussiness_id,
                $total_debit,
                $total_credit,
                $operationDetails,
                false
            );


            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Movimiento guardado',
                'data' => $movement
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
        }
    }
}
