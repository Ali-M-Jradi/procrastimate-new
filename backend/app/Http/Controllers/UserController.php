<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Group;
use App\Models\Comment;

class UserController extends Controller
{
    public function viewDashboard()
    {
        $user = Auth::user();
        $user->load('groups'); // Eager load the groups relationship
        $tasks = $user->tasks;
        $notifications = $user->notifications;
        $comments = $user->comments;

        // Use Eloquent relationship if available
        $coach = $user->coach ?? null;

        return view('dashboard.userDashboard', compact('user', 'tasks', 'notifications', 'comments', 'coach'));
    }

    public function showTaskCreationForm()
    {
        return view('task.create');
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id', // Allow assigning tasks only if the user exists
        ]);

        // Restrict assigning tasks to other users to coach and admin roles
        if ($request->assigned_to && !in_array(auth()->user()->role, ['coach', 'admin'])) {
            return redirect()->route('userDashboard')->with('error', 'You are not authorized to assign tasks to other users.');
        }

        $task = Task::create([
            'user_id' => $request->assigned_to ?? auth()->id(), // Assign to self if no user is specified
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => false,
        ]);

        return redirect()->route('userDashboard')->with('success', 'Task Created.');
    }

    public function showTaskUpdateForm($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        return view('task.update', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => 'required|date',
        ]);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
        ]);
        return redirect()->route('userDashboard')->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete();
        return redirect()->route('userDashboard')->with('success', 'Task cancelled.');
    }

    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('notifications.index', compact('notifications'));
    }

    public function showNotificationForm()
    {
        return view('notifications.create');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);
        auth()->user()->notifications()->create([
            'title' => $request->title,
            'message' => $request->message,
        ]);
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }

    public function createComment(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|max:255',
        ]);
        Comment::create([
            'user_id' => auth()->id(),
            'task_id' => $request->task_id,
            'comment' => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Comment created successfully!');
    }

    public function showCommentCreationForm($taskId)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($taskId);
        return view('comment.create_comment', compact('task'));
    }

    public function joinGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->attach($group);
        return redirect()->route('userDashboard')->with('success', 'Joined group successfully!');
    }

    public function leaveGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->detach($group);
        return redirect()->route('userDashboard')->with('success', 'Left group successfully!');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('homepage')->with('success', 'Logged out successfully!');
    }

    public function showGroupJoinForm()
    {
        $groups = Group::all();
        return view('group.join_groups', compact('groups'));
    }
}
