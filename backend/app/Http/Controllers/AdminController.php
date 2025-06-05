<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $tasks = Task::all(); // Or filter as needed for admin
        return view('admin.dashboard', compact('tasks'));
    }

    public function viewTask($id)
    {   
        $task = Task::findOrFail($id);
        return view('coach.task.view', compact('task'));
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $request->validate([
            'dueDate' => 'required|date',
        ]);
        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => $request->isCompleted ? true : false,
        ]);
        return redirect()->route('admin.task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Task deleted.');
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'dueDate' => 'required|date',
        ]);
        Task::create([
            'user_id' => $request->user_id,
            'admin_id' => Auth::user()->id, // if you track admin
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'isCompleted' => false,
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

    public function approveTask(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->admin->id)->firstOrFail();
        $task->update(['isCompleted' => true]);
        return redirect()->route('admin.dashboard')->with('success', 'Task approved successfully!');
    }
    
    public function rejectTask(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->admin->id)->firstOrFail();
        $task->update(['isCompleted' => false]);
        return redirect()->route('admin.dashboard')->with('success', 'Task rejected successfully!');
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $group = auth()->user()->groups()->create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        return redirect()->route('groups.view', ['id' => $group->id])->with('success', 'Group created successfully!');
    }

    public function viewGroup($id){
        $group = auth()->user()->groups()->findOrFail($id);
        return view('admin.group.view', compact('group'));
    }

    public function updateGroup(Request $request, $id)
    {
        $group = auth()->user()->groups()->findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $group->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        return redirect()->route('groups.view', ['id' => $id])->with('success', 'Group updated successfully!');
    }

    public function deleteGroup($id){
        $group = auth()->user()->groups()->findOrFail($id);
        $group->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Group deleted successfully!');
    }

    public function deleteComment($id)
    {
        $comment = auth()->user()->comments()->findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        return redirect()->route('admin.dashboard')->with('success', 'User created successfully!');
    }

    public function viewUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.view', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        return redirect()->route('admin.user.view', ['id' => $id])->with('success', 'User updated successfully!');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully!');
    }

    public function demoteCoachToUser($id)
    {
        $coach = User::findOrFail($id);
        $coach->role = 'user'; 
        $coach->save();
        return redirect()->route('admin.dashboard')->with('success', 'Coach demoted successfully!');
    }

    public function promoteUserToCoach($id)
    {
        $user = User::findOrFail($id);
        $user->role = 'coach'; 
        $user->save();
        return redirect()->route('admin.dashboard')->with('success', 'User promoted to coach successfully!');
    }
}