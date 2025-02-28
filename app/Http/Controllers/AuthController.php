<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Family;
use App\Models\Transaction;
use App\Models\Goal;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Registration attempt', ['data' => $request->all()]);

        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
                'account_type' => 'required|in:personal,family,join_family',
                'invitation_code' => 'nullable|required_if:account_type,join_family|string'
            ]);

            $family_id = null;

            Log::info('Validation passed');

            if ($request->account_type === 'join_family') {
                $family = Family::where('invitation_code', $request->invitation_code)->first();

                if (!$family) {
                    return back()->withErrors(['invitation_code' => 'invalid invitation code']);
                }

                $family_id = $family->id;
                // $request->merge(['account_type' => 'family']);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'account_type' => $request->account_type === 'join_family' ? 'family' : $request->account_type,
                'family_id' => $family_id
            ]);

            Log::info('User created', ['user_id' => $user->id]);

            Auth::login($user);
            Log::info('User logged in');

            if ($request->account_type === 'family') {
                return redirect()->route('family.create');
            } elseif ($request->account_type === 'join_family') {
                return redirect()->route('family.index');
            }

            // if ($request->account_type === 'family' && !$family_id) {

            //     Log::info('Redirecting to family creation');
            //     return redirect()->route('family.create')->with('success', 'registration successful, create family account');
            // } elseif ($request->account_type === 'family' && $family_id) {

            //     Log::info('Redirecting to familly dashboard');
            //     return redirect()->route('family.index')->with('success', 'Joined family successfully');
            // }

            Log::info('Redirection to profile');
            return redirect()->route('auth.profile')->with('success', 'Registration successful');
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
            return back()->withErrors(['error' => 'Registration failed' . $e->getMessage()]);
        }
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
            if (Auth::user()->account_type === 'personal') {
                return redirect()->route('auth.profile')->with('success', 'login successful');
            } elseif (Auth::user()->account_type === 'family') {
                return redirect()->route('family.index')->with('success', 'Redirecting to familly dashboard');
            }
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

        $totalTransactions = Transaction::where('user_id', $user->id)->count();
        $activeGoals = Goal::where('user_id', $user->id)->count();

        return view('auth.profile', [
            'totalTransactions' => $totalTransactions,
            'activeGoals' => $activeGoals,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string',
            'budget_method' => 'required|string'
        ]);

        $user->update($validated);

        redirect()->route('profile')->with('success', 'profile updated');
    }
}
