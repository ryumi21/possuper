<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::all();
        return view('backend.foods.index', compact('foods'));
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
        ]);

        Food::create($request->all());

        return redirect()->route('foods.index')->with('success', 'Menu/Item created successfully.');
    }

    public function edit($id)
    {
        $food = Food::findOrFail($id);
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
        ]);

        $food = Food::findOrFail($id);
        $food->update($request->all());

        return redirect()->route('foods.index')->with('success', 'Menu/Item updated successfully.');
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        $food->delete();

        return redirect()->route('foods.index')->with('success', 'Menu/Item deleted successfully.');
    }
}
