<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->orderBy('Oid', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'IsRole' => 'required|integer',
            'IsActive' => 'required|boolean',
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
        $user = User::with('role')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Code' => 'required|string|max:36',
            'Name' => 'required|string|max:36',
            'IsRole' => 'required|integer',
            'IsActive' => 'required|boolean',
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
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ]);
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->Password = bcrypt('123');
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset to 123 successfully.'
        ]);
    }
}
