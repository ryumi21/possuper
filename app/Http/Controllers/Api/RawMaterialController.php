<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        $materials = RawMaterial::with('itemUnit')->orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $materials
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:100',
            'Category' => 'nullable|string|max:50',
            'unit' => 'required|integer|exists:itemunit,Oid',
            'current_stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        $material = RawMaterial::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Raw Material created successfully.',
            'data' => $material
        ], 201);
    }

    public function show($id)
    {
        $material = RawMaterial::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $material
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Name' => 'required|string|max:100',
            'Category' => 'nullable|string|max:50',
            'unit' => 'required|integer|exists:itemunit,Oid',
            'current_stock' => 'nullable|numeric|min:0',
            'minimum_stock' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        $material = RawMaterial::findOrFail($id);
        $material->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Raw Material updated successfully.',
            'data' => $material
        ]);
    }

    public function destroy($id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Raw Material deleted successfully.'
        ]);
    }
}
