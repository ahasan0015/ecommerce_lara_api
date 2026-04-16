<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // যদি ফোন ফিল্ড থাকে তবে এখানে অ্যাড করুন
            'phone' => ['required', 'string', 'max:20'], 
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone, // যদি ফোন ফিল্ড ডাটাবেজে থাকে
            'role_id' => 3, // এখানে সরাসরি ৩ (Customer) সেট করে দেওয়া হলো
        ]);

        event(new Registered($user));

        Auth::login($user);

        // রেজিস্ট্রেশনের পর কোথায় যাবে সেটি এখানে ঠিক করুন
        return redirect(route('dashboard', absolute: false));
    }
}
