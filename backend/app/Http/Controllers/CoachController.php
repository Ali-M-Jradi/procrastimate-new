<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Group;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Coach;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CoachController extends Controller
{
    public function showGroupCreationForm()
    {
        return view('group.create_group');
    }

    public function viewDashboard()
    {
        $user = auth()->user();
        
        // Update task statuses for past due tasks
        Task::where('dueDate', '<', now())
            ->where('status', '!=', 'completed')
            ->update(['status' => 'out_of_date']);
            
        // 1. User tasks needing approval/rejection (created by users, not coaches/admins, coach_id null, isCompleted false, status 'pending')
        $userTasksToApprove = \App\Models\Task::whereNull('coach_id')
            ->where('status', 'pending')
            ->whereHas('user', function($q) { $q->where('role', 'user'); })
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
        // 2. Tasks created by this coach (coach_id = coach, user_id != coach, and user is a user)
        $coachCreatedTasks = \App\Models\Task::where('coach_id', $user->id)
            ->where('user_id', '!=', $user->id)
            ->whereHas('user', function($q) { $q->where('role', 'user'); })
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();
        // 3. Tasks assigned to this coach by an admin (user_id = coach, admin_id not null)
        $adminAssignedTasks = \App\Models\Task::where('user_id', $user->id)
            ->whereNotNull('admin_id')
            ->with(['admin'])
            ->orderBy('created_at', 'desc')
            ->get();
        $groups = $user->groups()->with('users')->orderBy('created_at', 'desc')->get();
        $notifications = \App\Models\Notification::where('to_user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $comments = \App\Models\Comment::with(['user', 'task'])->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('dashboard.coachDashboard', compact('user', 'groups', 'userTasksToApprove', 'coachCreatedTasks', 'adminAssignedTasks', 'notifications', 'comments'));
    }

    public function viewTask($id)
    {   
        $task = Task::findOrFail($id);
        return view('task.view', compact('task'));
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
        return redirect()->route('task.view', ['id' => $id])->with('success', 'Task updated successfully!');
    }

    public function deleteTask($id)
    {
        $task = Task::where('id', $id)->where('user_id', Auth::user()->coach->id)->firstOrFail();
        $task->delete();
        return redirect()->route('coach.dashboard')->with('success', 'Appointment cancelled.');
    }

    public function createTask(Request $request)
    {   
        $user = auth()->user();
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'dueDate' => 'required|date|after:today',
        ]);
        // Prevent coach from assigning tasks to themselves or to other coaches/admins
        $assignedUser = \App\Models\User::find($request->user_id);
        if ($assignedUser->id == $user->id || $assignedUser->role !== 'user') {
            return redirect()->back()->withErrors('Coaches can only assign tasks to users, not to themselves or other coaches/admins.');
        }
        Task::create([
            'user_id' => $request->user_id,
            'coach_id' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'dueDate' => $request->dueDate,
            'status' => 'pending',
        ]);
        return redirect()->route('coach.dashboard')->with('success', 'Task Created.');
    }

    public function sendNotification(Request $request)
    {
        auth()->user()->notifications()->create($request->all());
        return redirect()->route('notifications.view')->with('success', 'Notification created successfully!');
    }

    public function recieveNotifications()
    {
        $notifications = auth()->user()->notifications;
        return view('coach.notifications.view', compact('notifications'));
    }

    public function showTaskCreationForm()
    {
        $user = auth()->user();
        if ($user->role === 'coach') {
            $users = \App\Models\User::where('role', 'user')->get();
            return view('task.create', compact('users'));
        }
        return view('task.create');
    }

    public function showTaskUpdateForm($id)
    {
        $task = Task::where('coach_id', auth()->id())->findOrFail($id);
        return view('task.update', compact('task'));
    }

    public function createComment(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|max:255',
        ]);
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'task_id' => $request->task_id,
            'comment' => $request->comment,
        ]);
        // Send notification to the owner of the task if not the commenter
        $task = Task::find($request->task_id);
        if ($task && $task->user_id !== auth()->id()) {
            \App\Models\Notification::create([
                'to_user_id' => $task->user_id,
                'message' => 'New comment on your task: "' . $task->title . '"',
                'type' => 'comment',
                'notifiable_type' => Comment::class,
                'notifiable_id' => $comment->id,
                'read' => false,
            ]);
        }
        return redirect()->route('coach.dashboard')->with('success', 'Comment created successfully!');
    }

    public function showCommentCreationForm($taskId)
    {
        $task = Task::findOrFail($taskId);
        return view('comment.create_comment', compact('task'));
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

    public function showApprovalForm($id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();
        // Only allow if not approving own task
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot approve your own task.');
        }
        return view('task.approve', compact('task'));
    }

    public function showDeclineForm($id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();
        // Only allow if not declining own task
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot reject your own task.');
        }
        return view('task.reject', compact('task'));
    }

    public function approveTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot approve your own task.');
        }
        $task->update(['status' => 'approved']);
        // Send notification to the user
        \App\Models\Notification::create([
            'to_user_id' => $task->user_id,
            'message' => 'Your task "' . $task->title . '" was approved by ' . $user->name . '.',
            'type' => 'task_approved',
            'notifiable_type' => Task::class,
            'notifiable_id' => $task->id,
            'read' => false,
        ]);
        return redirect()->route('coach.dashboard')->with('success', 'Task approved successfully!');
    }
    
    public function rejectTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();
        if ($task->user_id == $user->id) {
            return redirect()->back()->withErrors('You cannot reject your own task.');
        }
        $task->update(['status' => 'out_of_date']);
        // Send notification to the user
        \App\Models\Notification::create([
            'to_user_id' => $task->user_id,
            'message' => 'Your task "' . $task->title . '" was rejected by ' . $user->name . '.',
            'type' => 'task_rejected',
            'notifiable_type' => Task::class,
            'notifiable_id' => $task->id,
            'read' => false,
        ]);
        return redirect()->route('coach.dashboard')->with('success', 'Task rejected successfully!');
    }

    public function createGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:groups',
            'description' => 'nullable|string|max:1000',
        ]);
        
        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        // Add the creator as the first member
        auth()->user()->groups()->attach($group->id);
        // Redirect to dashboard or group view (no groups.index route exists)
        return redirect()->route('coach.dashboard')->with('success', 'Group created successfully!');
    }

    public function viewGroup($id){
        $group = auth()->user()->groups()->findOrFail($id);
        return view('coach.group.view', compact('group'));
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
        
        return redirect()->route('coach.dashboard')->with('success', 'Group updated successfully!');
    }

    public function deleteGroup($id){
        $group = auth()->user()->groups()->findOrFail($id);
        $group->delete();
        return redirect()->route('coach.dashboard')->with('success', 'Group deleted successfully!');
    }
}
