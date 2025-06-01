<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewDashboard()
    {
        return view('admin.dashboard');
    }

    public function viewUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.view', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        return redirect()->route('admin.user.view', ['id' => $id])->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully!');
    }

    public function createUser()
    {
        return view('admin.user.create');
    }

    // public function storeUser(Request $request)
    // {
    //     $user = User::create($request->all());
    //     return redirect()->route('admin.user.view', ['id' => $user->id])->with('success', 'User created successfully!');
    // }
    public function viewTask($id)
    {
        $task = Task::findOrFail($id);
        return view('admin.task.view', compact('task'));
    }
    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->route('admin.task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }
    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Task deleted successfully!');
    }
    public function createTask()
    {
        return view('admin.task.create');
    }
    public function storeTask(Request $request)
    {
        $task = Task::create($request->all());
        return redirect()->route('admin.task.view', ['id' => $task->id])->with('success', 'Task created successfully!');
    }
    public function viewNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('admin.notifications.view', compact('notifications'));
    }
    public function createNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('admin.notifications.view')->with('success', 'Notification created successfully!');
    }
    public function assignTask(Request $request, $id)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::findOrFail($request->input('task_id'));
        $user = User::findOrFail($id);

        $task->user_id = $user->id;
        $task->save();

        return redirect()->route('admin.user.view', ['id' => $id])->with('success', 'Task assigned successfully!');
    }
    public function removeTask(Request $request, $id)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::findOrFail($request->input('task_id'));
        $task->user_id = null; // Unassign the task
        $task->save();

        return redirect()->route('admin.user.view', ['id' => $id])->with('success', 'Task removed successfully!');
    }
    public function logout()
    {
        auth()->logout();
        return redirect()->route('homepage')->with('success', 'Logged out successfully!');
    }
       
    public function createCoach()
    {
        return view('admin.coach.create');
    }
    // public function storeCoach(Request $request)
    // {
    //     $coach = Coach::create($request->all());
    //     return redirect()->route('admin.coach.view', ['id' => $coach->id])->with('success', 'Coach created successfully!');
    // }
    public function updateCoach(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->update($request->all());
        return redirect()->route('admin.coach.view', ['id' => $id])->with('success', 'Coach updated successfully!');
    }
    public function deleteCoach($id)
    {
        $coach = Coach::findOrFail($id);
        $coach->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Coach deleted successfully!');
    }
    public function promoteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = 'coach';
        $user->save();
        return redirect()->route('admin.user.view', ['id' => $id])->with('success', 'User promoted to coach successfully!');
    }
    public function demoteCoach(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->role = 'user';
        $coach->save();
        return redirect()->route('admin.coach.view', ['id' => $id])->with('success', 'Coach demoted to user successfully!');
    }
    public function viewCoach($id)
    {
        $coach = Coach::findOrFail($id);
        return view('admin.coach.view', compact('coach'));
    }
    public function viewCoaches()
    {
        $coaches = Coach::all();
        return view('admin.coach.index', compact('coaches'));
}
}