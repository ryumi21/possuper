<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
        $users = User::with('role')->where('IsPos', auth()->user()->IsPos)->get();
        $roles = Role::all();
        // Return view with users and all available roles for the dropdowns
        return view('backend.users.index', compact('users', 'roles'));
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

        return redirect()->route('users.index')->with('success', 'User created successfully.');
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

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $this->checkPermission();
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

    public function resetPassword($id)
    {
        $this->checkPermission();
        $user = User::findOrFail($id);
        $user->Password = bcrypt('123');
        $user->save();

        return redirect()->route('users.index')->with('success', 'Password reset to 123 successfully.');
    }
}
