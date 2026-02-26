<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::from('users as u')
            ->select('u.id', 'u.name', 'u.email', 'u.phone', 'r.name as role')
            ->join('roles as r', 'u.role_id', '=', 'r.id')
            ->orderBy('u.id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ], 200);
    }

    public  function store(Request $request)
    {

        // 1. Validation
        // For APIs, this automatically returns a 422 JSON response on failure
        $validatedData = $request->validate([
            'name'       => 'required|min:2|max:20',
            'email'      => ['required', 'email', 'unique:users'],
            'password'   => ['required', 'min:6', 'confirmed'],
            'role_id'    => ['required', 'exists:roles,id'], // Good practice to verify role exists
        ]);

        // 3. User Creation
        $user = User::create([
            'name' => $validatedData['name'],
            'email'      => $validatedData['email'],
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password), // Always hash your passwords!
            'role_id'    => $request->role_id
        ]);

        // 4. API Response
        return response()->json([
            'status'  => 'success',
            'message' => 'User registered successfully',
            'data'    => $user
        ], 201); // 201 Created
    }
    public function Update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|min:2|max:50',
            'phone'   => 'required|min:10',
            'role_id' => 'required|exists:roles,id'
        ]);

        // dd($request->all());
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'role_id' => $request->role_id
        ]);
        return response()->json([
            'status'  => 'success',
            'message' => 'User updated successfully',
            'data'    => $user
        ], 200);
    }

    public function destroy($id)
    {
        // ১. ইউজারটি ডাটাবেসে আছে কি না খুঁজে বের করা
        $user = User::find($id);

        // ২. যদি ইউজার না পাওয়া যায় তবে ৪0৪ এরর পাঠানো
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // ৩. ইউজারটি মুছে ফেলা
        $user->delete();

        // ৪. সাকসেস রেসপন্স পাঠানো
        return response()->json([
            'status'  => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }
}
