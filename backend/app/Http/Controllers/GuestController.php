<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return view('homepage');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        validate($request, [
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
        return view('auth.register');
    }

    public function register(Request $request)
    {
        validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = new \App\Models\User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');    
        $user->password = bcrypt($request->input('password'));
        $user->save();
        auth()->login($user);
        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }
}
