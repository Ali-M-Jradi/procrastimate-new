<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
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

    public function usersGroup(){
        return $this-> belongsToMany(User::class,'group_user');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
