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
        'status'
    ];

    public function requestor() {
        return $this->belongsTo(User::class, "userRequest_id");
    }

    public function receiver() {
        return $this->belongsTo(User::class, "user_id");
    }
}
