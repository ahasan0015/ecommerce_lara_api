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
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true, // এখানে string এর বদলে boolean পাঠানো ভালো
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role_id, // যদি কলাম খালি থাকে তবে ডিফল্ট manager হিসেবে যাবে
        ]
    ], 200);
}


     public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();
        return response()->json([
            'success'=> true,
            'message'=>'Logout successfully'
        ]);
    }
}
