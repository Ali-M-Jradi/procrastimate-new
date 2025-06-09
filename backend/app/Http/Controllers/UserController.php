<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Group;

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
        ]);
        
        $task = Task::create([
            'user_id' => auth()->id(),
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
            'content' => 'required|string|max:255',
        ]);
        auth()->user()->comments()->create([
            'content' => $request->content,
        ]);
        return redirect()->back()->with('success', 'Comment created successfully!');
    }

    public function showCommentCreationForm()
    {
        return view('comment.create_comment');
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
