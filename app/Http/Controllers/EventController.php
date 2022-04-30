<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', '=', Auth::user()->id)->get();

        return view('home', [
            'events' => $events
        ]);
    }

    public function store(EventRequest $request)
    {
        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if (!$request->boolean('fullDay')) {
            $startDate->setTimezone('UTC');
            $endDate->setTimezone('UTC');
        }

        $event = new Event();
        $event->user_id = Auth::user()->id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->startDate = $startDate->format('Y-m-d H:i:s');
        $event->endDate = $endDate->format('Y-m-d H:i:s');
        $event->fullDay = $request->boolean('fullDay');
        $event->recurring = false;
        $event->save();

        return redirect()->route('home');
    }
}
