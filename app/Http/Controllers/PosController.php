<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        return view('backend.pos.index');
    }

    public function data(Request $request)
    {
        $query = Product::where('IsActive', 1);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                  ->orWhere('Code', 'like', "%{$search}%");
            });
        }

        return response()->json($query->paginate(20));
    }
}
