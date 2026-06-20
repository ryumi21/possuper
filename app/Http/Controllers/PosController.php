<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Food;    
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        return view('backend.pos.index');
    }

    public function data(Request $request)
    {   $user = auth()->user();
        if($user->IsPos == 1){
            $query = Product::where('IsActive', 1);
        }else{
            $query = Food::where('IsActive', 1);
        }
        // dd($user->IsPos);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                  ->orWhere('Code', 'like', "%{$search}%");
            });
        }

        if ($request->has('type') && $request->type != '' && $request->type != 'All') {
            $query->where('Type', $request->type);
        }

        return response()->json($query->paginate(20));
    }
}
