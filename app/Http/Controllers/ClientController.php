<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Movement;
use App\Models\OperationDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function getAll(Request $request)
    {
        $clients = Client::where('bussiness_id', $request->bussiness_id)->get();
        return response()->json([
            'status' => true,
            'data' => $clients,
            'message' => 'OK'
        ]);
    }

    public function getById(Request $request)
    {
        $client = Client::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'messagge' => 'El cliente que intenta buscar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('bussiness_id', $request->bussiness_id)
            ->where('client_id', $request->id)->get();

        $client->editable = $clientMovements->count() == 0;

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'OK'
        ]);
    }

    public function getByCode(Request $request)
    {
        $client = Client::where('bussiness_id', $request->bussiness_id)
            ->where('code', 'like', '%' . $request->code . '%')->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta buscar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('bussiness_id', $request->bussiness_id)
            ->where('client_id', $request->code)->get();

        $client->editable = $clientMovements->count() == 0;

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'OK'
        ]);
    }

    public function getByDescription(Request $request)
    {
        $client = Client::where('bussiness_id', $request->bussiness_id)->where('description', 'like', '$' . $request->description . '%')->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta buscar no existe'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'OK'
        ]);
    }

    public function addClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'bussiness_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $client = Client::where('bussiness_id', $request->bussiness_id)->where('code', $request->code)->first();
        if ($client) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un cliente/proveedor con el código ' . $request->code
            ], 400);
        }

        $client = Client::create([
            'code' => $request->code,
            'name' => $request->name,
            'bussiness_id' => $request->bussiness_id
        ]);

        $client->save();

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'Cliente creado'
        ], 201);
    }

    public function editClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'bussiness_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $client = Client::where('bussiness_id', $request->bussiness_id)->where('id', $request->id)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta actualizar no existe'
            ], 400);
        }

        $client->code = $request->code;
        $client->name = $request->name;
        $client->update();

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'Cliente editado'
        ]);
    }

    public function deleteClient(Request $request)
    {
        $client = Client::where('bussiness_id', $request->bussiness_id)->where('id', $request->id)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta eliminar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('bussiness_id', $request->bussiness_id)->where('client_id', $request->id)->get();
        if ($clientMovements && $clientMovements->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente tiene movimientos, no se puede eliminar'
            ]);
        }

        $client->delete();

        return response()->json([
            'status' => true,
            'message' => 'Cliente eliminado'
        ]);
    }
}
