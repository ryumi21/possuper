<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        // Return view with users and all available roles for the dropdowns
        return view('backend.users.index', compact('users', 'roles'));
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

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = "SELECT * FROM user where Oid = ($id)";
        $a = db::select($user);
        $user = User::where('Oid', $id)->get();
        $user1 = User::where('IsRole', 2)
                        // ->wherein('IsActive', 1)
                        ->get();
        dd($a, $user,$user1);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
