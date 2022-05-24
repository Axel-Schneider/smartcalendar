<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'description',
        'complete',
        'todo_id',
    ];

    public function list(){
        return $this->hasOne(ToDo::class);
    }

    public function getFills(){
        return [
            'id' => $this->id,
            'description' => $this->description,
            'complete' => $this->complete,
            'todo_id' => $this->todo_id,
        ];
    }
}
