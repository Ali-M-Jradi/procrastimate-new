<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\Group;

class UserController extends Controller
{
    public function dashboard()
    {
        $tasks = Auth::user()->tasks()->get();
        return view('user.dashboard', compact('tasks'));
    }

    public function viewTask($id)
    {   
        $task = Task::findOrFail($id);
        return view('user.task.view', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $request->validate([
            'dueDate' => 'required|date',
        ]);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => $request->isCompleted ? true : false,
        ]);
        return redirect()->route('task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task cancelled.');
    }

    public function createTask(Request $request)
    {   
        $request->validate([
            'dueDate' => 'required|date',
        ]);
        Task::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => false,
        ]);
        return redirect()->route('dashboard')->with('success', 'Task Created.');
    }

    public function sendNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }

    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('user.notifications.view', compact('notifications'));
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
    
    public function joinGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->attach($group);
        return redirect()->back()->with('success', 'Joined group successfully!');
    }

    public function leaveGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);
        $group = Group::findOrFail($request->group_id);
        auth()->user()->groups()->detach($group);
        return redirect()->back()->with('success', 'Left group successfully!');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('homepage')->with('success', 'Logged out successfully!');
    }
}
