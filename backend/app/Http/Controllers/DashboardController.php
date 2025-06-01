<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->guestDashboard();
        }

        switch ($user->role) {
            case 'admin':
                return $this->adminDashboard($user);
            case 'coach':
                return $this->coachDashboard($user);
            case 'user':
                return $this->userDashboard($user);
            default:
                return $this->guestDashboard();
        }
    }

    protected function guestDashboard()
    {
        // Return guest dashboard view or data
        return view('dashboard.guest');
    }

    protected function userDashboard($user)
    {
        // Return user dashboard view or data
        return view('dashboard.user', compact('user'));
    }

    protected function coachDashboard($user)
    {
        // Return coach dashboard view or data
        return view('dashboard.coach', compact('user'));
    }

    protected function adminDashboard($user)
    {
        // Return admin dashboard view or data
        return view('dashboard.admin', compact('user'));
    }
}
