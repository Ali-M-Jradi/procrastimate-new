<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_Task extends Model
{
    protected $table = 'group_tasks';
    protected $fillable = [
        'task_id',
        'group_id',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function task()
    {
        return $this->belongsTo(Tasks::class, 'task_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'group_id');
    }

    public function user()
    {
        return $this->belongsTo(Group_Users::class, 'user_id');
    }

    public function userAssignedBy(){
        return $this->belongsTo(User::class,'assigned_by');
    }
}
