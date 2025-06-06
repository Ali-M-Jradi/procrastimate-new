<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    // Adjust these fields to match your project requirements
    protected $fillable = [
        'title',           // Task title/name
        'description',     // Task description
        'dueDate',         // Due date (if your DB column is 'dueDate')
        'isCompleted',     // Boolean or status field
        'user_id',         // The user assigned to the task
        'coach_id',        // The coach who assigned/oversees the task (if applicable)
        'admin_id',        // The admin who assigned/oversees the task (if applicable)
        // Add/remove fields as needed for your schema
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // If you have a group-task relationship
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_task', 'task_id', 'group_id');
    }
}
