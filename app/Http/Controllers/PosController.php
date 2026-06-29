<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Food;    
use Illuminate\Http\Request;

class PosController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $posMenu = \App\Models\Menu::where('Fitur', 'Mesin Kasir (POS)')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$posMenu || !is_array($user->allowed_menus) || !in_array($posMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkPermission();
        return view('backend.pos.index');
    }

    public function data(Request $request)
    {
        $this->checkPermission();
        $user = auth()->user();
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
