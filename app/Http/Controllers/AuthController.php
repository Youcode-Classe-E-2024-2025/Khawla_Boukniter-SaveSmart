<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Family;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'account_type' => 'required|in:personal,family',
            'invitation_code' => 'required_if:account_type,join_family|string'
        ]);

        if ($request->account_type === 'join_family') {
            $family = Family::where('invitation_code', $request->invitation_code)->first();

            if (!$family) {
                return back()->withErrors(['invitation_code' => 'invalid invitation code']);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => $request->account_type,
            'family_id' => isset($family) ? $family->id : null
        ]);

        Auth::login($user);

        if ($request->account_type === 'family') {
            return redirect()->route('family.create')->with('success', 'registration successful, create family account');
        }

        return redirect()->route('profile')->with('success', 'Registration successful');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('profile')->with('success', 'login successful');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'logout successful');
    }

    public function profile()
    {
        $user = Auth::user();

        return view('auth.profile');
    }
}
