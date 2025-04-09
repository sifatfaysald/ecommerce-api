<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    // Register new user with role
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,admin,salesman',  // Ensure role is valid
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'token' => $user->createToken('EcommerceApp')->plainTextToken,
            'user' => $user
        ]);
    }

    // Login and generate token
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json([
                'token' => $user->createToken('EcommerceApp')->plainTextToken,
                'user' => $user
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}