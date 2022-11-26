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
        return response()->json([
            'status' => true,
            'data' => Client::all(),
            'message' => 'OK'
        ]);
    }

    public function getById(Request $request, $id)
    {
        $client = Client::whereId($id)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'messagge' => 'El cliente que intenta buscar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('client_id', $id)->get();

        $client->editable = $clientMovements->count() == 0;

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'OK'
        ]);
    }

    public function getByCode(Request $request, $code)
    {
        $client = Client::where('code', 'like', '%' . $code . '%')->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta buscar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('client_id', $code)->get();

        $client->editable = $clientMovements->count() == 0;

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'OK'
        ]);
    }

    public function getByDescription(Request $request, $description)
    {
        $client = Client::where('description', 'like', '$' . $description . '%')->first();
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
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $client = Client::where('code', $request->code)->first();
        if ($client) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe un cliente/proveedor con el código ' . $request->code
            ], 400);
        }

        $client = Client::create([
            'code' => $request->code,
            'name' => $request->name
        ]);

        $client->save();

        return response()->json([
            'status' => true,
            'data' => $client,
            'message' => 'Cliente creado'
        ], 201);
    }

    public function editClient(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $client = Client::whereId($id)->first();
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

    public function deleteClient(Request $request, $id)
    {
        $client = Client::whereId($id)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente que intenta eliminar no existe'
            ], 400);
        }

        $clientMovements = Movement::where('client_id', $id)->get();
        if ($clientMovements && $clientMovements->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'El cliente tiene movimientos, no se puede eliminar'
            ]);
        }

        $client->delete();
    }
}
