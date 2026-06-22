<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $users = User::with(['role'])->orderBy('Oid', 'desc')->get();
        
        // Map allowed_menus IDs to actual Menu models to keep compatibility with the frontend badges
        foreach ($users as $u) {
            $allowedIds = is_array($u->allowed_menus) ? $u->allowed_menus : [];
            $u->menus = \App\Models\Menu::whereIn('Oid', $allowedIds)->where('Is_Active', 1)->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function show($id)
    {
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
        $request->validate([
            'menu_ids' => 'nullable|array',
            'menu_ids.*' => 'integer|exists:menu,Oid'
        ]);

        $user = User::findOrFail($id);
        
        // Update the allowed_menus array column directly
        $user->allowed_menus = $request->input('menu_ids', []);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully.',
            'data' => $user
        ]);
    }
}
