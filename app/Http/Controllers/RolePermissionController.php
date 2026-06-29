<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;

class RolePermissionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rolePermissionMenu = Menu::where('Fitur', 'Role Permission')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$rolePermissionMenu || !is_array($user->allowed_menus) || !in_array($rolePermissionMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }

        $menus = Menu::where('Is_Active', 1)->where('IsPos', $user->IsPos)->orderBy('Category', 'asc')->orderBy('Fitur', 'asc')->get();
        return view('backend.rolepermissions.index', compact('menus'));
    }
}
