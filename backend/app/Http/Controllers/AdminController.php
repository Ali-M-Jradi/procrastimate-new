<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function viewDashboard()
    {
        $tasks = Task::all();
        $users = User::where('role', '!=', 'admin')->get();
        $groups = Group::all();
        $coaches = User::where('role', 'coach')->get();
        
        return view('dashboard.adminDashboard', compact('tasks', 'users', 'groups', 'coaches'));
    }

    // User Management Methods
    public function showUserCreationForm()
    {
        return view('admin.user.create');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,coach',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User created successfully!');
    }

    public function showUpdateUserForm($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,coach',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User updated successfully!');
    }

    public function showDeleteUserForm($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.delete', compact('user'));
    }

    public function deleteUser($id)
    {
        if ($id === Auth::id()) {
            return redirect()->back()
                ->withErrors(['error' => 'You cannot delete your own admin account.']);
        }

        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User deleted successfully!');
    }

    // Coach Management Methods
    public function showPromoteUserForm($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        return view('admin.user.promote', compact('user'));
    }

    public function promoteUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $user->role = 'coach';
        $user->save();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'User promoted to coach successfully!');
    }

    public function showDemoteCoachForm($id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        return view('admin.coach.demote', compact('coach'));
    }

    public function demoteCoach($id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        $coach->role = 'user';
        $coach->save();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Coach demoted to user successfully!');
    }

    public function showCoachCreationForm()
    {
        return view('admin.coach.create');
    }

    public function createCoach(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'coach',
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Coach created successfully!');
    }

    public function showUpdateCoachForm($id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        return view('admin.coach.edit', compact('coach'));
    }

    public function updateCoach(Request $request, $id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $coach->id,
        ]);
        
        $coach->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Coach updated successfully!');
    }

    public function showDeleteCoachForm($id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        return view('admin.coach.delete', compact('coach'));
    }

    public function deleteCoach($id)
    {
        $coach = User::where('role', 'coach')->findOrFail($id);
        $coach->delete();
        
        return redirect()->route('admin.dashboard')
            ->with('success', 'Coach deleted successfully!');
    }
}