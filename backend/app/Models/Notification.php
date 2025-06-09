<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'to_user_id',
        'message',
        'read',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    // Relationship: recipient
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
