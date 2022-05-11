<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EventController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if ($request->fullDay != 'on') {
            $startDate = $startDate->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = $endDate->setTimezone('UTC')->format('Y-m-d H:i:s');
        }else{
            $startDate = $startDate->format('Y-m-d 00:00:00');
            $endDate = $endDate->addDay()->format('Y-m-d 00:00:00');
        }

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

    public function update(Request $request, Event $event){
        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if ($request->fullDay != 'on') {
            $startDate = $startDate->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = $endDate->setTimezone('UTC')->format('Y-m-d H:i:s');
        }else{
            $startDate = $startDate->format('Y-m-d 00:00:00');
            $endDate = $endDate->addDay()->format('Y-m-d 00:00:00');
        }

        $event->title = $request->title;
        $event->description = $request->description;
        $event->startDate = $startDate;
        $event->endDate = $endDate;
        $event->fullDay = $request->boolean('fullDay');
        $event->save();

        return redirect()->route('home');
    }
}
