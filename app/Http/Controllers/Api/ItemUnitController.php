<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class ItemUnitController extends Controller
{
    public function index()
    {
        $units = ItemUnit::orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $units
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code' => 'required|string|max:100',
            'Name' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $unit = ItemUnit::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item Unit created successfully.',
            'data' => $unit
        ], 201);
    }

    public function show($id)
    {
        $unit = ItemUnit::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $unit
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code' => 'required|string|max:100',
            'Name' => 'required|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        $unit = ItemUnit::findOrFail($id);
        $unit->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Item Unit updated successfully.',
            'data' => $unit
        ]);
    }

    public function destroy($id)
    {
        $unit = ItemUnit::findOrFail($id);
        $unit->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item Unit deleted successfully.'
        ]);
    }
}
