<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function getAll(Request $request)
    {
        $units = Unit::where('bussiness_id', $request->bussiness_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $units
        ]);
    }

    public function getById(Request $request)
    {
        $unit = Unit::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$unit) {
            return response()->json([
                'status' => false,
                'message' => 'La unidad de medida con idL ' . $request->id . ' no existe'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $unit
        ]);
    }

    public function addUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bussiness_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
            'unitary' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);

        $unit = Unit::where('bussiness_id', $request->bussiness_id)
            ->where('slug', $slug)->first();

        if ($unit) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe una unidad de medida con ese nombre'
            ]);
        }

        $unit = Unit::create([
            'name' => $request->name,
            'abbreviation' => $request->abbreviation,
            'unitary' => boolval( $request->unitary),
            'bussiness_id' => $request->bussiness_id,
            'slug' => $slug
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unidad de medida creada',
            'data' => $unit
        ]);
    }

    public function editUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bussiness_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:255',
            'unitary' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $slug = Str::slug($request->name);

        $unit = Unit::where('bussiness_id', $request->bussiness_id)
            ->where('slug', $slug)
            ->whereNot('id', $request->id)->first();

        if ($unit) {
            return response()->json([
                'status' => false,
                'message' => 'Ya existe una unidad de medida el ese nombre'
            ]);
        }

        $unit = Unit::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();

        if (!$unit) {
            return response()->json([
                'status' => false,
                'message' => 'No existe una unidad de medida el id: ' . $request->id
            ]);
        }

        // $unit = Unit::create([
        //     'name' => $request->name,
        //     'abbreviation' => $request->abbreviation,
        //     'unitary' => $request->unitary,
        //     'bussiness_id' => $request->bussiness_id,
        //     'slug' => $slug
        // ]);

        $unit->name = $request->name;
        $unit->abbreviation = $request->abbreviation;
        $unit->unitary = boolval($request->unitary);
        $unit->slug = $slug;
        $unit->save();

        return response()->json([
            'status' => true,
            'message' => 'Unidad de medida actualizada',
            'data' => $unit
        ]);
    }

    public function deleteUnit(Request $request)
    {
        $unit = Unit::where('bussiness_id', $request->bussiness_id)
            ->where('id', $request->id)->first();
        
        if(!$unit) {
            return response()->json([
                'status' => false,
                'message' => 'No existe la unidad de medida con id: ' . $request->id
            ]);
        }

        $unit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Unidad de medida eliminada'
        ]);
    }
}
