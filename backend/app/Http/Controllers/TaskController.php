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
        
        // Check if the task status needs to be updated based on due date
        $task = $this->checkTaskStatus($task);
        
        // Filter users based on role
        if ($user->role === 'admin') {
            // Admins can see all users
            $users = \App\Models\User::all();
        } elseif ($user->role === 'coach') {
            // Coaches can only see regular users
            $users = \App\Models\User::where('role', 'user')->get();
        } else {
            // Regular users can only see themselves
            $users = \App\Models\User::where('id', $user->id)->get();
        }
        
        return view('task.update', compact('task', 'users', 'user'));
    }

    public function updateTask(Request $request, $id)
    {
        $user = auth()->user();
        $task = Task::findOrFail($id);
        
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => ['required', 'date'],
            'status' => ['required', 'in:pending,approved,completed,out_of_date'],
        ];
        
        // Add user_id validation based on role
        if ($user->role === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        } elseif ($user->role === 'coach') {
            $rules['user_id'] = 'required|exists:users,id';
            
            // Check if the selected user is a regular user
            if ($request->has('user_id') && \App\Models\User::where('id', $request->user_id)->where('role', '!=', 'user')->exists()) {
                return redirect()->back()->withErrors('Coaches can only assign tasks to regular users.');
            }
        } else {
            // Regular users can only assign tasks to themselves
            $request->merge(['user_id' => $user->id]);
        }
        
        $request->validate($rules);
        
        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'user_id' => $request->user_id,
            'status' => $request->status,
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
        // Check if the task status needs to be updated based on due date
        $task = $this->checkTaskStatus($task);
        return view('task.view', compact('task'));
    }

    // Check and update task status based on due date
    protected function checkTaskStatus(Task $task)
    {
        $dueDate = \Carbon\Carbon::parse($task->dueDate);
        $today = now();
        
        // If past due date and not completed, mark as out_of_date
        if ($today->gt($dueDate) && $task->status !== 'completed') {
            $task->status = 'out_of_date';
            $task->save();
        }
        
        return $task;
    }
}
