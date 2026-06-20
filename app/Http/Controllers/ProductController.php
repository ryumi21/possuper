<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products  = Product::with('productMaterials.unit')->get();
        $materials = \App\Models\RawMaterial::where('status', 'active')->orderBy('Name', 'asc')->get();
        $units     = \App\Models\ItemUnit::where('status', 'active')->orderBy('Name', 'asc')->get();
        return view('backend.products.index', compact('products', 'materials', 'units'));
    }

    public function create()
    {
        return view('backend.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('backend.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'Type' => 'nullable|string|max:36',
            'Price' => 'required|numeric',
            'IsStock' => 'required|boolean',
            'IsActive' => 'required|boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function barcode($id)
    {
        $product = Product::findOrFail($id);
        $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
        $barcode = $generator->getBarcode($product->Code, $generator::TYPE_CODE_128);

        return view('backend.products.barcode', compact('product', 'barcode'));
    }
}
