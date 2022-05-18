<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function shareds() {
        return $this->hasMany(Shared::class);
    }

    public function sharedWith() {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function recurringPatern() {
        return $this->hasOne(RecurringPatern::class);
    }

    public function toDo() {
        return $this->hasOne(ToDo::class);
    }

    public function startDate() {
        return Carbon::parse($this->startDate);
    }

    public function endDate() {
        return Carbon::parse($this->endDate);
    }
}
