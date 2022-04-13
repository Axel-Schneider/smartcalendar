<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'startDate',
        'endDate',
        'fullDay',
        'recurring',
        'endRecurrence',
        'user_id',
        'todo_id',
        'recurringPatern_id',
    ];
}
