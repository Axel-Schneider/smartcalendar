<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\User;
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
    public function store(EventRequest $request)
    {
        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if ($request->fullDay != 'on') {
            $startDate = $startDate->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = $endDate->setTimezone('UTC')->format('Y-m-d H:i:s');
        } else {
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
        if ($request->sharedWith != null) {
            foreach ($request->sharedWith as $userId) {
                if ($request->commonWith == null || !in_array($userId, $request->commonWith)) {
                    $event->sharedWith()->attach($userId, ['status' => 'shared']);
                }
            }
        }
        if ($request->commonWith != null) {
            foreach ($request->commonWith as $userId) {
                $event->commonWith()->attach($userId, ['status' => 'common']);
            }
        }


        return redirect()->route('home');
    }

    public function update(EventRequest $request, Event $event)
    {
        function asPower(Event $event)
        {
            if ($event->owner->id == Auth::user()->id) return true;
            if ($event->commonWith()->where('user_id', Auth::user()->id)->exists()) return true;
            return false;
        }
        if (!asPower($event)) return response()->json(['error' => 'Unauthorized'], 403);

        $startDate = Carbon::parse($request->startDate . ' ' . $request->timezone);
        $endDate = Carbon::parse($request->endDate . ' ' . $request->timezone);
        if ($request->fullDay != 'on') {
            $startDate = $startDate->setTimezone('UTC')->format('Y-m-d H:i:s');
            $endDate = $endDate->setTimezone('UTC')->format('Y-m-d H:i:s');
        } else {
            $startDate = $startDate->format('Y-m-d 00:00:00');
            $endDate = $endDate->addDay()->format('Y-m-d 00:00:00');
        }

        $event->title = $request->title;
        $event->description = $request->description;
        $event->startDate = $startDate;
        $event->endDate = $endDate;
        $event->fullDay = $request->boolean('fullDay');
        $event->save();

        foreach ($event->sharedWith as $user) {
            if (Auth::user()->contacts()->contains($user) || $event->owner->id == Auth::user()->id) {
                $event->sharedWith()->updateExistingPivot($user->id, ['status' => 'none']);
            }
        }

        if ($event->owner->id == Auth::user()->id) {
            foreach ($event->commonWith as $user) {
                $event->commonWith()->updateExistingPivot($user->id, ['status' => 'none']);
            }
        }

        if ($request->sharedWith != null) {
            foreach ($request->sharedWith as $userId) {
                if (Auth::user()->contacts()->contains($userId)) {
                    if ($event->relationsShared()->where('user_id', '=', $userId)->exists()) {
                        $event->sharedWith()->updateExistingPivot($userId, ['status' => 'shared']);
                    } else {
                        $event->sharedWith()->attach($userId, ['status' => 'shared']);
                    }
                }
            }
        }
        if ($request->commonWith != null && $event->owner->id == Auth::user()->id) {
            foreach ($request->commonWith as $userId) {
                if (Auth::user()->contacts()->contains($userId)) {
                    if ($event->relationsShared()->where('user_id', '=', $userId)->exists()) {
                        $event->commonWith()->updateExistingPivot($userId, ['status' => 'common']);
                    } else {
                        $event->commonWith()->attach($userId, ['status' => 'common']);
                    }
                }
            }
        }

        return redirect()->route('home');
    }
}
