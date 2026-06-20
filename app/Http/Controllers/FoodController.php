<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::with('productMaterials.unit')->get();
        $materials = \App\Models\RawMaterial::where('status', 'active')->orderBy('Name', 'asc')->get();
        $units = \App\Models\ItemUnit::where('status', 'active')->orderBy('Name', 'asc')->get();
        return view('backend.foods.index', compact('foods', 'materials', 'units'));
    }

    public function create()
    {
        return view('backend.foods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'BuyPrice' => 'nullable|numeric',
            'SellPrice' => 'nullable|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
            'material_name' => 'nullable|string|max:100',
            'create_cost' => 'nullable|numeric|min:0',
        ]);

        $food = Food::create($request->all());

        if ($request->filled('material_name')) {
            $food->productMaterial()->create([
                'Name' => $request->material_name,
                'Create_Cost' => $request->create_cost ?? 0,
            ]);
        }

        return redirect()->route('foods.index')->with('success', 'Menu/Item created successfully.');
    }

    public function edit($id)
    {
        $food = Food::with('productMaterial')->findOrFail($id);
        return view('backend.foods.edit', compact('food'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'BuyPrice' => 'nullable|numeric',
            'SellPrice' => 'nullable|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
            'material_name' => 'nullable|string|max:100',
            'create_cost' => 'nullable|numeric|min:0',
        ]);

        $food = Food::findOrFail($id);
        $food->update($request->all());

        if ($request->filled('material_name')) {
            \App\Models\ProductMaterial::updateOrCreate(
                ['Product_Id' => $food->Oid],
                [
                    'Name' => $request->material_name,
                    'Create_Cost' => $request->create_cost ?? 0,
                ]
            );
        } else {
            $food->productMaterial()->delete();
        }

        return redirect()->route('foods.index')->with('success', 'Menu/Item updated successfully.');
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        $food->productMaterial()->delete();
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Menu/Item deleted successfully.');
    }
}
