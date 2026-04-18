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
            ->paginate(10); // <-- 10 users per page

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
            // 'password'   => ['required', 'min:6', 'confirmed'],
            'role_id'    => ['required', 'exists:roles,id'], // Good practice to verify role exists
            'phone'      => 'nullable|string|max:20',
        ]);

        // 3. User Creation
        $user = User::create([

            'name'          => $validatedData['name'],
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

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string',
            'password' => 'sometimes|min:6|confirmed',
            'role_id' => 'sometimes|integer'
        ]);

        if ($request->has('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        // if user not found 404 error
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found'
            ], 404);
        }

        //delete user
        $user->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'User deleted successfully'
        ], 200);
    }
    public function show($id)
    {
        // find user by id
        $user = User::find($id);

        // if user not found 404 error
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'User details retrieved successfully',
            'data'    => $user
        ], 200);
    }
}
