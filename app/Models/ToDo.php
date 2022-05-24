<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'complete',
        'todo_id',
    ];

    public function tasks() {
        return $this->hasMany(Task::class, 'todo_id', 'id');
    }

    public function events() {
        return $this->belongsToMany(Event::class);
    }
}
