<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('productMaterials.unit')->orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $foods
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code'      => 'required|string|max:36',
            'Name'      => 'required|string|max:36',
            'Type'      => 'nullable|string|max:36',
            'Price'     => 'required|numeric',
            'BuyPrice'  => 'required|numeric',
            'SellPrice' => 'required|numeric',
            'IsStock'   => 'required|boolean',
            'IsActive'  => 'required|boolean',
            'materials'           => 'nullable|array',
            'materials.*.name'    => 'required_with:materials|string|max:100',
            'materials.*.qty'     => 'nullable|numeric|min:0',
            'materials.*.unit_id' => 'nullable|integer',
        ]);

        $food = Food::create($request->only([
            'Code', 'Name', 'Type', 'Price', 'BuyPrice', 'SellPrice', 'IsStock', 'IsActive'
        ]));

        foreach ($request->input('materials', []) as $m) {
            if (!empty($m['name'])) {
                $food->productMaterials()->create([
                    'Name'        => $m['name'],
                    'Create_Cost' => $m['qty'] ?? 0,
                    'ItemUnit'    => $m['unit_id'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Food item created successfully.',
            'data'    => $food->load('productMaterials.unit')
        ], 201);
    }

    public function show($id)
    {
        $food = Food::with('productMaterials.unit')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $food
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code'      => 'required|string|max:36',
            'Name'      => 'required|string|max:36',
            'Type'      => 'nullable|string|max:36',
            'Price'     => 'required|numeric',
            'BuyPrice'  => 'required|numeric',
            'SellPrice' => 'required|numeric',
            'IsStock'   => 'required|boolean',
            'IsActive'  => 'required|boolean',
            'materials'           => 'nullable|array',
            'materials.*.name'    => 'required_with:materials|string|max:100',
            'materials.*.qty'     => 'nullable|numeric|min:0',
            'materials.*.unit_id' => 'nullable|integer',
        ]);

        $food = Food::findOrFail($id);
        $food->update($request->only([
            'Code', 'Name', 'Type', 'Price', 'BuyPrice', 'SellPrice', 'IsStock', 'IsActive'
        ]));

        // Delete old and re-insert
        $food->productMaterials()->delete();
        foreach ($request->input('materials', []) as $m) {
            if (!empty($m['name'])) {
                $food->productMaterials()->create([
                    'Name'        => $m['name'],
                    'Create_Cost' => $m['qty'] ?? 0,
                    'ItemUnit'    => $m['unit_id'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Food item updated successfully.',
            'data'    => $food->load('productMaterials.unit')
        ]);
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        $food->productMaterials()->delete();
        $food->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Food item deleted successfully.'
        ]);
    }
}
