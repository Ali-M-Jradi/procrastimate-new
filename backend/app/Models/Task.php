<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = [
        'name',
        'description',
        'status',
        'due_date',
        'priority',
        'assigned_to',
        'assigned_by',
    ];

    public function groupTasks()
    {
        return $this->hasMany(Group_Task::class, 'task_id');
    }

    public function userAssignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
    public function userAssignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
