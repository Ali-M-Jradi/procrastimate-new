<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Comments extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'post_id',
        'comment',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function replies()
    {
        return $this->hasMany(Comments::class, 'parent_id');
    }
    public function notificationsSent()
    {
        return $this->hasMany(Notification::class, 'comment_id');
    }
    public function notificationsReceived()
    {
        return $this->hasMany(Notification::class, 'comment_id');
    }
}
