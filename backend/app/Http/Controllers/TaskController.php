<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function showTaskCreationForm()
    {
        return view('task.create');
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => ['required', 'date', 'after:today'],
        ]);
        $task = Task::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => false,
        ]);
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Task Created.');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Task Created.');
        } else {
            return redirect()->route('userDashboard')->with('success', 'Task Created.');
        }
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
            'dueDate' => ['required', 'date', 'after:today'],
        ]);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
        ]);
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Task updated successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Task updated successfully!');
        } else {
            return redirect()->route('userDashboard')->with('success', 'Task updated successfully!');
        }
    }

    public function deleteTask($id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete();
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Task cancelled.');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Task cancelled.');
        } else {
            return redirect()->route('userDashboard')->with('success', 'Task cancelled.');
        }
    }

    public function viewTask($id)
    {
        $task = Task::findOrFail($id);
        return view('task.view', compact('task'));
    }
}
