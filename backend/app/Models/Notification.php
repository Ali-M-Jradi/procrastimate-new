<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'message',
    ];

    public function fromUserId(){
        return $this->belongsTo(User::class,'from_user_id');
    }

    public function toUserId(){
        return $this->belongsTo(User::class,'to_user_id');
    }
    
}
