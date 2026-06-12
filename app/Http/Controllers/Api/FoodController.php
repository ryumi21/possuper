<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $foods
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'BuyPrice' => 'required|numeric',
            'SellPrice' => 'required|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
        ]);

        $food = Food::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Food item created successfully.',
            'data' => $food
        ], 201);
    }

    public function show($id)
    {
        $food = Food::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $food
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'BuyPrice' => 'required|numeric',
            'SellPrice' => 'required|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
        ]);

        $food = Food::findOrFail($id);
        $food->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Food item updated successfully.',
            'data' => $food
        ]);
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        $food->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Food item deleted successfully.'
        ]);
    }
}
