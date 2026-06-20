<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\ItemUnit;
use Illuminate\Http\Request;

class RawMaterialController extends Controller
{
    public function index()
    {
        $units = ItemUnit::where('status', 'active')->orderBy('Name', 'asc')->get();
        return view('backend.rawmaterials.index', compact('units'));
    }
}
