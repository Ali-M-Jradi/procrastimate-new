<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
    ];

    // Relationship: sender
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // Relationship: recipient
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
