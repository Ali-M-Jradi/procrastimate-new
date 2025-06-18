<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function updateAllTaskStatuses()
    {
        // Find all tasks with due dates in the past that aren't completed
        $outdatedTasks = Task::where('dueDate', '<', now())
                           ->where('status', '!=', 'completed')
                           ->update(['status' => 'out_of_date']);
        
        return redirect()->back()->with('success', 'Task statuses updated successfully');
    }
}
