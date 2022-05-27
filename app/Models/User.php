<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'firstname',
        'description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function contacts() {
        $first = $this->belongsToMany(User::class, 'contacts', 'user_id', 'userRequest_id')->where('status', '=', 'accept');
        $second = $this->belongsToMany(User::class, 'contacts', 'userRequest_id', 'user_id')->where('status', '=', 'accept');
        return $first->get()->merge($second->get());
    }
    
    public function contact($user) {
        $first = $this->hasMany(Contact::class, 'user_id')->where('status', '=', 'accept')->where('userRequest_id', '=', $user->id)->get();
        return ($first->count() > 0) ? $first->first() : $this->hasMany(Contact::class, 'userRequest_id')->where('status', '=', 'accept')->where('user_id', '=', $user->id)->get()->first();
    }
    
    public function contactsWaiting() {
        $first = $this->belongsToMany(User::class, 'contacts', 'user_id', 'userRequest_id')->where('status', '=', 'waiting');
        $second = $this->belongsToMany(User::class, 'contacts', 'userRequest_id', 'user_id')->where('status', '=', 'waiting');
        return $first->get()->merge($second->get());
    }

    public function contactProposals() {
        $response = User::all()->diff($this->contacts())->where('id', '<>', $this->id)->take(5);
        return $response;
    }

    public function shareds() {
        return $this->belongsToMany(Event::class, 'event_user')->whereNot('status', '=', 'none')->withTimestamps();
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function isContact($user) {
        return $this->contacts()->where('id', '=', $user->id)->count() > 0;
    }

    public function isContactWaiting($user) {
        return $this->contactsWaiting()->where('id', '=', $user->id)->count() > 0;
    }
    
    function hasPower(Event $event)
    {
        if ($event->owner->id == $this->id) return true;
        if ($event->commonWith()->where('user_id', $this->id)->exists()) return true;
        return false;
    }
}
