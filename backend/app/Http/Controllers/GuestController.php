<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if (auth()->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            // For tests, return no content if requested
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->noContent();
            }
            // Redirect to dashboard based on role
            $role = auth()->user()->role;
            if ($role === 'coach') {
                return redirect()->route('coach.dashboard')->with('success', 'Login successful!');
            } elseif ($role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login successful!');
            }
            return redirect()->route('userDashboard')->with('success', 'Login successful!');
        }
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
    }

    public function showRegistrationForm()
    {
        $roles = ['user', 'coach']; // or fetch from DB if needed
        return view('register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user,coach',
        ]);
        $user = new \App\Models\User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');    
        $user->password = bcrypt($request->input('password'));
        $user->role = $request->input('role');
        $user->save();
        auth()->login($user);
        // For tests, return no content if requested
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->noContent();
        }
        // Redirect to dashboard based on role
        if ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Registration successful!');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Registration successful!');
        }
        return redirect()->route('userDashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // For tests, return no content if requested
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->noContent();
        }
        return redirect()->route('login');
    }
}
