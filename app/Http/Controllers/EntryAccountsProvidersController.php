<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Client;
use App\Models\EntryAccountsProviders;
use Illuminate\Http\Request;

class EntryAccountsProvidersController extends Controller
{
    public function getAll(Request $request)
    {
        $entryAccountProviders = EntryAccountsProviders::with('account', 'client')
            ->where('bussiness_id', $request->bussiness_id)->get();

        foreach ($entryAccountProviders as $entry) {
            if ($entry->account) {
                $entry->account_number = $entry->account->number;
                $entry->account_name = $entry->account->name;
            }
            if ($entry->client) {
                $entry->client_code = $entry->client->code;
                $entry->client_name = $entry->client->name;
            }

            unset($entry->account);
            unset($entry->client);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $entryAccountProviders
        ]);
    }

    public function getById(Request $request)
    {
        $entry = EntryAccountsProviders::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$entry) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la información que solicita',
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $entry
        ]);
    }

    public static function addEntryAccountsProviders($bussiness_id)
    {
        $entry = new EntryAccountsProviders();
        $entry->bussiness_id = $bussiness_id;
        $entry->concept = 'IMPORTADORA';
        $entry->save();

        $entry1 = new EntryAccountsProviders();
        $entry1->bussiness_id = $bussiness_id;
        $entry1->concept = 'GASTOS FINANCIEROS';
        $entry1->save();

        $entry2 = new EntryAccountsProviders();
        $entry2->bussiness_id = $bussiness_id;
        $entry2->concept = 'TRANSPORTACIÓN';
        $entry2->save();

        $entry3 = new EntryAccountsProviders();
        $entry3->bussiness_id = $bussiness_id;
        $entry3->concept = 'MANIPULACIÓN';
        $entry3->save();
    }

    public static function deleteEntryAccountsProvider($bussiness_id)
    {
        EntryAccountsProviders::where('bussiness_id', $bussiness_id)->delete();
    }

    public function editEntryAccountsProviders(Request $request)
    {
        $entry = EntryAccountsProviders::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$entry) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la información que está intentando modificar'
            ]);
        }

        if ($request->account_id > 0) {
            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->account_id)->first();
            if (!$account) {
                return response()->json([
                    'status' => false,
                    'message' => 'La cuenta con id: ' . $request->account_id . ' no existe'
                ]);
            }
            $account = Account::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->account_id)
                ->whereIn('account_group_id', ['2'])->get();
            if (!$account) {
                return response()->json([
                    'status' => false,
                    'message' => 'La cuenta con id: ' . $request->account_id . ' no está declarada como cuenta para aceptar pagos'
                ]);
            }
            $entry->account_id = $request->account_id;
        }

        if ($request->client_id > 0) {
            $client = Client::where('bussiness_id', $request->bussiness_id)
                ->where('id', $request->client_id)->first();
            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => 'El proveedor con id: ' . $request->account_id . ' no existe'
                ]);
            }
            $entry->client_id = $request->client_id;
        }

        $entry->update();
        
        return response()->json([
            'status' => true,
            'message' => 'Datos actualizados',
            'data' => $entry
        ]);
    }
}
