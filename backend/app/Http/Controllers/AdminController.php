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
        // Show all tasks where the admin is the assigned user (no created_by column)
        $tasks = \App\Models\Task::where('user_id', $admin->id)
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Always create as regular user regardless of form input
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'coach', // Always create as coach regardless of form input
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
        $comment = \App\Models\Comment::create($request->only('user_id', 'task_id', 'comment'));
        // Send notification to the owner of the task if not the commenter
        $task = \App\Models\Task::find($request->task_id);
        if ($task && $task->user_id !== (int)$request->user_id) {
            \App\Models\Notification::create([
                'to_user_id' => $task->user_id,
                'message' => 'New comment on your task: "' . $task->title . '"',
                'type' => 'comment',
                'notifiable_type' => \App\Models\Comment::class,
                'notifiable_id' => $comment->id,
                'read' => false,
            ]);
        }
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

    // Task approval/rejection by admin
    public function approveTask(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $user = auth()->user();
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot approve your own task.');
        }
        $task->update(['isCompleted' => true]);
        // Send notification to the user
        \App\Models\Notification::create([
            'to_user_id' => $task->user_id,
            'message' => 'Your task "' . $task->title . '" was approved by admin ' . $user->name . '.',
            'type' => 'task_approved',
            'notifiable_type' => \App\Models\Task::class,
            'notifiable_id' => $task->id,
            'read' => false,
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Task approved successfully!');
    }
    public function rejectTask(Request $request, $id)
    {
        $task = \App\Models\Task::findOrFail($id);
        $user = auth()->user();
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot reject your own task.');
        }
        $task->update(['isCompleted' => false]);
        // Send notification to the user
        \App\Models\Notification::create([
            'to_user_id' => $task->user_id,
            'message' => 'Your task "' . $task->title . '" was rejected by admin ' . $user->name . '.',
            'type' => 'task_rejected',
            'notifiable_type' => \App\Models\Task::class,
            'notifiable_id' => $task->id,
            'read' => false,
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Task rejected successfully!');
    }

    public function showTaskCreationForm()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'user')->get();
            $coaches = \App\Models\User::where('role', 'coach')->get();
            // Exclude self from both lists
            $users = $users->where('id', '!=', $user->id);
            $coaches = $coaches->where('id', '!=', $user->id);
            return view('task.create', compact('users', 'coaches'));
        }
        return view('task.create');
    }
}