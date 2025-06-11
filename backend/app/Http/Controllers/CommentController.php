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
        Comment::create([
            'user_id' => auth()->id(),
            'task_id' => $request->task_id,
            'comment' => $request->comment,
        ]);
        return redirect()->route('userDashboard')->with('success', 'Comment created successfully!');
    }

    public function showCommentCreationForm(Request $request)
    {
        $taskId = $request->input('task_id');
        if ($taskId) {
            $task = Task::where('user_id', auth()->id())->findOrFail($taskId);
            return view('comment.create', compact('task'));
        } else {
            $tasks = Task::where('user_id', auth()->id())->get();
            return view('comment.select_task', compact('tasks'));
        }
    }
}
