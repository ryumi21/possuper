<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rolesMenu = \App\Models\Menu::where('Fitur', 'Roles')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$rolesMenu || !is_array($user->allowed_menus) || !in_array($rolesMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }

        return view('backend.roles.index');
    }
}
