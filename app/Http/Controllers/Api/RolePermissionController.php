<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $rolePermissionMenu = \App\Models\Menu::where('Fitur', 'Role Permission')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$rolePermissionMenu || !is_array($user->allowed_menus) || !in_array($rolePermissionMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkPermission();
        $users = User::with(['role'])->where('IsPos', auth()->user()->IsPos)->orderBy('Oid', 'desc')->get();
        
        // Map allowed_menus IDs to actual Menu models to keep compatibility with the frontend badges
        foreach ($users as $u) {
            $allowedIds = is_array($u->allowed_menus) ? $u->allowed_menus : [];
            $u->menus = \App\Models\Menu::whereIn('Oid', $allowedIds)->where('Is_Active', 1)->where('IsPos', $u->IsPos)->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $this->checkPermission();
        $user = User::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'assigned_menu_ids' => is_array($user->allowed_menus) ? $user->allowed_menus : []
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission();
        $request->validate([
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'integer|exists:menu,Oid'
        ]);

        $user = User::findOrFail($id);
        
        // Validasi agar modul yang diberikan sesuai dengan IsPos dari user target
        $menuIds = $request->input('menu_ids', []);
        if (!empty($menuIds)) {
            $invalidCount = \App\Models\Menu::whereIn('Oid', $menuIds)
                ->where('IsPos', '!=', $user->IsPos)
                ->count();
            if ($invalidCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terdapat modul yang tidak sesuai dengan tipe POS user (Retail/Cafe)!'
                ], 422);
            }
        }

        // Update the allowed_menus array column directly
        $user->allowed_menus = $menuIds;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully.',
            'data' => $user
        ]);
    }
}
