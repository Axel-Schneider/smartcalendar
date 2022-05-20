<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
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
    public function store(EventRequest $request)
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
        if($request->sharedWith != null){
            foreach ($request->sharedWith as $userId) {
                if($request->commonWith == null || !in_array($userId ,$request->commonWith)){
                    $event->sharedWith()->attach($userId, ['status' => 'shared']);
                }
            }
        }
        if($request->commonWith != null){
            foreach ($request->commonWith as $userId) {
                $event->commonWith()->attach($userId, ['status' => 'common']);
            }
        }


        return redirect()->route('home');
    }

    public function update(EventRequest $request, Event $event){
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
        
        foreach($event->sharedWith as $user){
            $event->sharedWith()->updateExistingPivot($user->id, ['status' => 'none']);
        }

        if($event->owner->id == Auth::user()->id){
            foreach($event->commonWith as $user){
                $event->commonWith()->updateExistingPivot($user->id, ['status' => 'none']);
            }
        }

        if($request->sharedWith != null){
            foreach ($request->sharedWith as $userId) {
                if($event->relationsShared()->where('user_id', '=', $userId)->exists()){
                    $event->sharedWith()->updateExistingPivot($userId, ['status' => 'shared']);
                }else{
                    $event->sharedWith()->attach($userId, ['status' => 'shared']);
                }
            }
        }
        if($request->commonWith != null){
            foreach ($request->commonWith as $userId) {
                if($event->relationsShared()->where('user_id', '=', $userId)->exists()){
                    $event->commonWith()->updateExistingPivot($userId, ['status' => 'common']);
                }else{
                    $event->commonWith()->attach($userId, ['status' => 'common']);
                }
            }
        }

        return redirect()->route('home');
    }
}
