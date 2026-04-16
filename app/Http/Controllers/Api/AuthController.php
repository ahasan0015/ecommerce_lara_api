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
        // ১. ভ্যালিডেশন
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6', // পাসওয়ার্ডের জন্য মিনিমাম লেংথ দেওয়া ভালো
            'phone'    => 'required|string|max:20'
        ]);

        // ২. ইউজার তৈরি করা (নিরাপদ পদ্ধতি)
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'phone'    => $validatedData['phone'],
            'password' => bcrypt($request->password), // অথবা Hash::make($request->password)
            'role_id'  => 2, // ডিফল্ট কোনো রোল থাকলে এখানে সেট করে দিন
        ]);

        // ৩. রেসপন্স পাঠানো
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully!',
            'data'    => $user
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


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successfully'
        ]);
    }
}
