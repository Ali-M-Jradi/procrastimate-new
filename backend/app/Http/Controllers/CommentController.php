<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
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
        // Send notification to the owner of the task
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
        // Redirect based on role
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.comment.index')->with('success', 'Comment created successfully!');
        } elseif ($user->role === 'coach') {
            return redirect()->route('coach.dashboard')->with('success', 'Comment created successfully!');
        } else {
            return redirect()->route('userDashboard')->with('success', 'Comment created successfully!');
        }
    }

    public function showCommentCreationForm(Request $request)
    {
        $tasks = Task::all(); // Show all tasks for selection
        return view('comment.create', compact('tasks'));
    }

    public function deleteComment($id)
    {
        $comment = Comment::findOrFail($id);
        $user = auth()->user();
        // Only allow delete if user is owner, coach, or admin
        if ($comment->user_id === $user->id || $user->role === 'admin' || $user->role === 'coach') {
            $comment->delete();
            if ($user->role === 'admin') {
                return redirect()->route('admin.comment.index')->with('success', 'Comment deleted successfully!');
            } elseif ($user->role === 'coach') {
                return redirect()->route('coach.dashboard')->with('success', 'Comment deleted successfully!');
            } else {
                return redirect()->route('userDashboard')->with('success', 'Comment deleted successfully!');
            }
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
