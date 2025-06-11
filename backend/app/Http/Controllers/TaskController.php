<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function showTaskCreationForm()
    {
        $user = auth()->user();
        $users = collect();
        $coaches = collect();
        if ($user->role === 'admin') {
            $users = \App\Models\User::where('role', 'user')->get();
            $coaches = \App\Models\User::where('role', 'coach')->get();
            // Exclude self from both lists
            $users = $users->where('id', '!=', $user->id);
            $coaches = $coaches->where('id', '!=', $user->id);
            return view('task.create', compact('users', 'coaches'));
        } elseif ($user->role === 'coach') {
            $users = \App\Models\User::where('role', 'user')->get();
            return view('task.create', compact('users'));
        }
        // user role
        return view('task.create');
    }

    public function createTask(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => ['required', 'date', 'after:today'],
        ];
        if ($user->role === 'admin' || $user->role === 'coach') {
            $rules['user_id'] = 'required|exists:users,id';
        }
        $request->validate($rules);

        if ($user->role === 'admin') {
            // Prevent admin from assigning tasks to themselves
            if ($request->user_id == $user->id) {
                return redirect()->back()->withErrors('Admins cannot assign tasks to themselves.');
            }
            $task = Task::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'description' => $request->description,
                'dueDate' => $request->dueDate,
                'status' => 'pending',
            ]);
            return redirect()->route('admin.dashboard')->with('success', 'Task Created.');
        } elseif ($user->role === 'coach') {
            $task = Task::create([
                'user_id' => $request->user_id,
                'coach_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'dueDate' => $request->dueDate,
                'status' => 'pending',
            ]);
            return redirect()->route('coach.dashboard')->with('success', 'Task Created.');
        } else {
            // user can only assign to self
            $task = Task::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'dueDate' => $request->dueDate,
                'status' => 'pending',
            ]);
            return redirect()->route('userDashboard')->with('success', 'Task Created.');
        }
    }

    public function showTaskUpdateForm($id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);
        $users = \App\Models\User::all();
        return view('task.update', compact('task', 'users', 'user'));
    }

    public function updateTask(Request $request, $id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => ['required', 'date', 'after:today'],
            'user_id' => 'required|exists:users,id',
        ];
        $request->validate($rules);
        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'user_id' => $request->user_id,
        ];
        $task->update($updateData);
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
