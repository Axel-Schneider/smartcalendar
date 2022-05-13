<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'userRequest_id',
        'user_id',
        'status',
        'blocker_id',
    ];

    public function requestor() {
        return $this->belongsTo(User::class, "userRequest_id");
    }

    public function receiver() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function blocker() {
        return $this->belongsTo(User::class, "blocker_id");
    }
}
