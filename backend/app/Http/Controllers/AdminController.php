<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get this patient's appointments
        $tasks = Auth::user()->coach->tasks()->get();
        return view('caoch.dashboard', compact('task'));
    }

    public function viewTask($id)
    {   
        $task = Task::findOrFail($id);
        return view('coach.task.view', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('task_id', Auth::user()->admin->id)->firstOrFail();
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
        $task = Task::where('id', $id)->where('user_id', Auth::user()->admin->id)->firstOrFail();
        $task->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Appointment cancelled.');
    }

    public function createTask(Request $request)
    {   
        $request->validate([
        'user_id' => 'required|exists:user,id',
        'dueDate' => 'required|date',
        ]);
        Task::create([
        'user' => Auth::user()->admin->id,
        'dueDate' => $request->dueDate,
        'isCompleted' => 'pending',
        ]);
        return redirect()->route('admin.dashboard')->with('success', 'Task Created.');
    }

    public function sendNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }

    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('admin.notifications.view', compact('notifications'));
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