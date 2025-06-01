<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class CoachController extends Controller
{
    public function viewDashboard()
    {
        return view('coach.dashboard');
    }

    public function viewTask($id)
    {
        $task = Task::findOrFail($id);
        return view('coach.task.view', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        return redirect()->route('coach.task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('coach.dashboard')->with('success', 'Task deleted successfully!');
    }

    public function createTask()
    {
        return view('coach.task.create');
    }

    public function storeTask(Request $request)
    {
        $task = Task::create($request->all());
        return redirect()->route('coach.task.view', ['id' => $task->id])->with('success', 'Task created successfully!');
    }

    public function viewNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('coach.notifications.view', compact('notifications'));
    }

    public function createNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('coach.notifications.view')->with('success', 'Notification created successfully!');
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

        return redirect()->back()->with('success', 'Task assigned to user successfully!');
    }

    public function removeTask(Request $request, $id)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::findOrFail($request->input('task_id'));

        if ($task->user_id == $id) {
            $task->user_id = null;
            $task->save();
            return redirect()->back()->with('success', 'Task removed from user successfully!');
        }

        return redirect()->back()->with('error', 'Task does not belong to this user.');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('homepage')->with('success', 'Logged out successfully!');
    }
}
