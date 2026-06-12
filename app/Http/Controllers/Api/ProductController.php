<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $products
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

        $product = Product::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully.',
            'data' => $product
        ], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $product
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

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully.',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    }
}
