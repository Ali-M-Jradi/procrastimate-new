<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on the user's role
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'user':
                    return redirect()->route('userDashboard');
                case 'coach':
                    return redirect()->route('coach.dashboard');
                default:
                    Auth::logout(); // Logout if the role is invalid
                    return back()->withErrors([
                        'role' => 'Invalid role detected.',
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
