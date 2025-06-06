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
            return redirect()->intended('dashboard');
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
        return redirect()->route('userDashboard')->with('success', 'Registration successful!');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
