<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $rolesMenu = \App\Models\Menu::where('Fitur', 'Roles')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$rolesMenu || !is_array($user->allowed_menus) || !in_array($rolesMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkPermission();
        $roles = Role::orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission();
        $request->validate([
            'Code' => 'required|string|max:36|unique:role,Code',
            'Name' => 'required|string|max:36',
        ]);

        $role = Role::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.',
            'data' => $role
        ], 201);
    }

    public function show($id)
    {
        $this->checkPermission();
        $role = Role::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission();
        $request->validate([
            'Code' => 'required|string|max:36|unique:role,Code,' . $id . ',Oid',
            'Name' => 'required|string|max:36',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully.',
            'data' => $role
        ]);
    }

    public function destroy($id)
    {
        $this->checkPermission();
        $role = Role::findOrFail($id);
        
        // Prevent deleting roles currently assigned to users
        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete role because it is currently assigned to one or more users.'
            ], 422);
        }

        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully.'
        ]);
    }
}
