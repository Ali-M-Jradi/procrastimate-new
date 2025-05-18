<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group_User extends Model
{
    protected $table = 'group_users';
    protected $fillable = [
        'user_id',
        'group_id',
        'role_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
