<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountGroupController extends Controller
{
    public function getGroups(Request $request)
    {
        return response()->json([
            'status' => true,
            'data' => AccountGroup::all(),
            'message' => 'OK'
        ]);
    }

    // public function getGroupById(Request $request, $id)
    // {
    //     $group = AccountGroup::whereId($id)->first();
    //     if (!$group) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'El grupo que intenta buscar no existe'
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'data' => $group,
    //         'message' => 'OK'
    //     ]);
    // }

    // public function addGroup(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'start' => 'required|numeric',
    //         'end' => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $validator->errors()
    //         ]);
    //     }

    //     if ($request->end <= $request->start) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'La cuenta final no puede ser menor que la cuenta inicial',
    //         ]);
    //     }

    //     $startEnd = AccountGroup::where('end', '<=',  $request->start)->get();

    //     return response()->json([
    //         'status' => false,
    //         // 'message' => 'El rango de cuenta ya es parte de un grupo existente',
    //         'data' => $startEnd
    //     ]);

    //     if ($startEnd > 0) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'El rango de cuenta ya es parte de un grupo existente',
    //             'data' => $startEnd
    //         ]);
    //     }

    //     $group = AccountGroup::create([
    //         'name' => $request->name,
    //         'start' => $request->start,
    //         'end' => $request->end
    //     ]);

    //     $group->save();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $group,
    //         'message' => 'Grupo creado'
    //     ], 201);
    // }

    // public function editGroup(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|max:255',
    //         'start' => 'required|numeric',
    //         'end' => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => $validator->errors()
    //         ]);
    //     }

    //     $group = AccountGroup::whereId($id)->first();
    //     if (!$group) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'El grupo que intenta editar no existe'
    //         ]);
    //     }

    //     $group->name = $request->name;
    //     $group->start = $request->start;
    //     $group->end = $request->end;

    //     $group->update();

    //     return response()->json([
    //         'status' => true,
    //         'data' => $group,
    //         'message' => 'Grupo actualizado'
    //     ]);
    // }

    // public function deleteGroup(Request $request, $id)
    // {
    //     $group = AccountGroup::whereId($id)->first();
    //     if (!$group) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'El grupo que intenta eliminar no existe'
    //         ]);
    //     }

    //     $accounts = Account::where('account_group_id', $id)->first();
    //     if ($accounts) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'El grupo que intenta editar tiene asociada al menos una cuenta, no se puede eliminar'
    //         ]);
    //     }

    //     $group->delete();

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Grupo eliminado'
    //     ]);
    // }
}
