<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user) {
            $user->load('role'); // Include role data if relationship exists
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                // Add more config data here in the future if needed
            ]
        ]);
    }
}
