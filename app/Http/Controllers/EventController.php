<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index() {
        $events = Event::where('user_id', '=', Auth::user()->id)->get();

        return view('home', [
            'events' => $events
        ]);
    }
}
