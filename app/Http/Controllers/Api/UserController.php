<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function checkPermission()
    {
        $user = auth()->user();
        $usersMenu = \App\Models\Menu::where('Fitur', 'Users')->where('IsPos', $user->IsPos)->first();
        if (!$user || ($user->IsRole != 1 && (!$usersMenu || !is_array($user->allowed_menus) || !in_array($usersMenu->Oid, $user->allowed_menus)))) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        $this->checkPermission();
        $users = User::with('role')->where('IsPos', auth()->user()->IsPos)->orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $this->checkPermission();
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'IsRole' => 'required|integer',
            'IsActive' => 'required|boolean',
            'IsPos' => 'required|integer|in:1,2',
        ]);

        $user = User::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'data' => $user
        ], 201);
    }

    public function show($id)
    {
        $this->checkPermission();
        $user = User::with('role')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->checkPermission();
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'IsRole' => 'required|integer',
            'IsActive' => 'required|boolean',
            'IsPos' => 'required|integer|in:1,2',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $this->checkPermission();
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ]);
    }

    public function resetPassword($id)
    {
        $this->checkPermission();
        $user = User::findOrFail($id);
        $user->Password = bcrypt('123');
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset to 123 successfully.'
        ]);
    }
}
