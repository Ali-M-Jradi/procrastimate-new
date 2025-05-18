<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_users');
    }
    public function groupUsers()
    {
        return $this->hasMany(Group_User::class);
    }
    public function tasksAssignedTo()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function tasksAssignedBy()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
