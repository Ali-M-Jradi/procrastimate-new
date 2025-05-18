<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable = [
        'submitted_by',
        'user_id',       // the reported person
        'group_id',      // nullable
        'reason',
    ];

    public function userId(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function group(){
        return $this->belongsTo(Group::class,'group_id');
    }

    public function userSubmittedBy(){
        return $this->belongsTo(User::class,'submitted_by');
    }
}
