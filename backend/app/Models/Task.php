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
        'user_id',         // <-- add this line

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
    public function comments()
    {
        return $this->hasMany(Comment::class, 'task_id');
    }
}
