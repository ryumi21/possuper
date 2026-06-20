<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productMaterials.unit')->orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data'   => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code'     => 'required|string|max:36',
            'Name'     => 'required|string|max:36',
            'Type'     => 'nullable|string|max:36',
            'Price'    => 'required|numeric',
            'IsStock'  => 'required|boolean',
            'IsActive' => 'required|boolean',
            'materials'           => 'nullable|array',
            'materials.*.name'    => 'required_with:materials|string|max:100',
            'materials.*.qty'     => 'nullable|numeric|min:0',
            'materials.*.unit_id' => 'nullable|integer',
        ]);

        $product = Product::create($request->only([
            'Code', 'Name', 'Type', 'Price', 'BuyPrice', 'SellPrice', 'IsStock', 'IsActive'
        ]));

        foreach ($request->input('materials', []) as $m) {
            if (!empty($m['name'])) {
                $product->productMaterials()->create([
                    'Name'        => $m['name'],
                    'Create_Cost' => $m['qty'] ?? 0,
                    'ItemUnit'    => $m['unit_id'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Product created successfully.',
            'data'    => $product->load('productMaterials.unit')
        ], 201);
    }

    public function show($id)
    {
        $product = Product::with('productMaterials.unit')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code'     => 'required|string|max:36',
            'Name'     => 'required|string|max:36',
            'Type'     => 'nullable|string|max:36',
            'Price'    => 'required|numeric',
            'IsStock'  => 'required|boolean',
            'IsActive' => 'required|boolean',
            'materials'           => 'nullable|array',
            'materials.*.name'    => 'required_with:materials|string|max:100',
            'materials.*.qty'     => 'nullable|numeric|min:0',
            'materials.*.unit_id' => 'nullable|integer',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only([
            'Code', 'Name', 'Type', 'Price', 'BuyPrice', 'SellPrice', 'IsStock', 'IsActive'
        ]));

        // Delete old and re-insert
        $product->productMaterials()->delete();
        foreach ($request->input('materials', []) as $m) {
            if (!empty($m['name'])) {
                $product->productMaterials()->create([
                    'Name'        => $m['name'],
                    'Create_Cost' => $m['qty'] ?? 0,
                    'ItemUnit'    => $m['unit_id'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Product updated successfully.',
            'data'    => $product->load('productMaterials.unit')
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->productMaterials()->delete();
        $product->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    }
}
