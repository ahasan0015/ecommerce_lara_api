<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'phone' => 'required'
        ]);
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password)
        // ]);
        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        // 1️⃣ Validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2️⃣ Find user by email
        $user = User::where('email', $request->email)->first();

        // 3️⃣ Check user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // 4️⃣ Check password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid password'
            ], 401);
        }

        // 5️⃣ Create token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
