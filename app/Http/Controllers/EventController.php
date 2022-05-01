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
        // dd($request->all());
        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if ($request->fullDay != 'on') {
            $startDate = $startDate->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = $endDate->setTimezone('UTC')->format('Y-m-d H:i:s');
        }else{
            $startDate = $startDate->format('Y-m-d 00:00:00');
            // dd($endDate);
            $endDate = $endDate->addDay()->format('Y-m-d 00:00:00');
            // dd($endDate);
        }

        // dd($request->all(), $startDate, $endDate);
        $event = new Event();
        $event->user_id = Auth::user()->id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->startDate = $startDate;
        $event->endDate = $endDate;
        $event->fullDay = $request->boolean('fullDay');
        $event->recurring = false;
        $event->save();

        return redirect()->route('home');
    }
}
