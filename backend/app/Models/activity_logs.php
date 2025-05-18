<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Activity_Logs extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
