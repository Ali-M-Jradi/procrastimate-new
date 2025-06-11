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
        $admin = Auth::user();
        // Show all tasks where the admin is the creator or assigned user
        $tasks = \App\Models\Task::where('user_id', $admin->id)
            ->orWhere('created_by', $admin->id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
        $users = User::where('role', '!=', 'admin')->get();
        $groups = Group::all();
        $coaches = User::where('role', 'coach')->get();
        $comments = \App\Models\Comment::with(['user', 'task'])->orderByDesc('created_at')->get();
        $notifications = \App\Models\Notification::with(['toUser'])->orderByDesc('created_at')->get();
        
        return view('dashboard.adminDashboard', compact('admin', 'tasks', 'users', 'groups', 'coaches', 'comments', 'notifications'));
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

    // --- COMMENT MANAGEMENT ---
    public function listComments() {
        $comments = \App\Models\Comment::with(['user', 'task'])->orderByDesc('created_at')->get();
        return view('admin.comment.index', compact('comments'));
    }
    public function showCreateCommentForm() {
        $tasks = \App\Models\Task::all();
        $users = \App\Models\User::all();
        return view('admin.comment.create', compact('tasks', 'users'));
    }
    public function createComment(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|max:255',
        ]);
        \App\Models\Comment::create($request->only('user_id', 'task_id', 'comment'));
        return redirect()->route('admin.comment.index')->with('success', 'Comment created successfully!');
    }
    public function showEditCommentForm($id) {
        $comment = \App\Models\Comment::findOrFail($id);
        $tasks = \App\Models\Task::all();
        $users = \App\Models\User::all();
        return view('admin.comment.edit', compact('comment', 'tasks', 'users'));
    }
    public function updateComment(Request $request, $id) {
        $comment = \App\Models\Comment::findOrFail($id);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|max:255',
        ]);
        $comment->update($request->only('user_id', 'task_id', 'comment'));
        return redirect()->route('admin.comment.index')->with('success', 'Comment updated successfully!');
    }
    public function deleteComment($id) {
        $comment = \App\Models\Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('admin.comment.index')->with('success', 'Comment deleted successfully!');
    }
    // --- NOTIFICATION MANAGEMENT ---
    public function listNotifications() {
        $notifications = \App\Models\Notification::orderByDesc('created_at')->get();
        return view('admin.notification.index', compact('notifications'));
    }
    public function showCreateNotificationForm() {
        $users = \App\Models\User::all();
        return view('admin.notification.create', compact('users'));
    }
    public function createNotification(Request $request) {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);
        \App\Models\Notification::create($request->only('to_user_id', 'message'));
        return redirect()->route('admin.notification.index')->with('success', 'Notification created successfully!');
    }
    public function showEditNotificationForm($id) {
        $notification = \App\Models\Notification::findOrFail($id);
        $users = \App\Models\User::all();
        return view('admin.notification.edit', compact('notification', 'users'));
    }
    public function updateNotification(Request $request, $id) {
        $notification = \App\Models\Notification::findOrFail($id);
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);
        $notification->update($request->only('to_user_id', 'message'));
        return redirect()->route('admin.notification.index')->with('success', 'Notification updated successfully!');
    }
    public function deleteNotification($id) {
        $notification = \App\Models\Notification::findOrFail($id);
        $notification->delete();
        return redirect()->route('admin.notification.index')->with('success', 'Notification deleted successfully!');
    }
}