<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;

class RolePermissionController extends Controller
{
    public function index()
    {
        $menus = Menu::where('Is_Active', 1)->orderBy('Category', 'asc')->orderBy('Fitur', 'asc')->get();
        return view('backend.rolepermissions.index', compact('menus'));
    }
}
