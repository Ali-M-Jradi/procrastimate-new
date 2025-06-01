<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function viewDashboard()
    {
        return view('user.dashboard');
    }

    public function viewTask($id)
    {
        $task = Task::findOrFail($id);
        return view('user.task.view', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->route('task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('dashboard')->with('success', 'Task deleted successfully!');
    }

    public function createTask()
    {
        return view('user.task.create');
    }

    public function storeTask(Request $request)
    {
        $task = Task::create($request->all());
        return redirect()->route('task.view', ['id' => $task->id])->with('success', 'Task created successfully!');
    }

    public function viewNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('user.notifications.view', compact('notifications'));
    }

    public function createNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->route('homepage')->with('success', 'Logged out successfully!');
    }
}
