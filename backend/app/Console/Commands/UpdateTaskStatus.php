<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class UpdateTaskStatus extends Command
{
    protected $signature = 'tasks:update-status';
    protected $description = 'Update task statuses based on due dates';

    public function handle()
    {
        $this->info('Updating task statuses...');
        
        // Find all tasks with due dates in the past that aren't completed
        $outdatedTasks = Task::where('dueDate', '<', now())
                           ->where('status', '!=', 'completed')
                           ->get();
                           
        foreach($outdatedTasks as $task) {
            $task->status = 'out_of_date';
            $task->save();
        }
        
        $this->info('Updated ' . $outdatedTasks->count() . ' task(s) to out_of_date status.');
    }
}
