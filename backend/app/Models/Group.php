<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'is private',
    ];
    public function userCreatedBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function groupTasks(){
        return $this->hasMany(Group_Task::class);
    }

    // Standardized relationship for users
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'group_user', 'group_id', 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
