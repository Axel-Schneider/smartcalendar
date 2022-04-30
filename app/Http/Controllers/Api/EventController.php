<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request) {
        $start = $request->query('start');
        $end = $request->query('end');

        $events = Event::where('user_id', '=', Auth::user()->id)
            ->where('startDate', '>=', $start)
            ->where('startDate', '<=', $end)
            ->get();
        $results = [];
        foreach($events as $event) {
            $results[] = [
                "id" => $event->id,
                "title" => $event->title,
                "start" => $event->startDate()->format('c'),
                "end" => $event->endDate()->format('c'),
                "fullDay" => $event->fullDay,
                "recurring" => $event->recurring,
            ];
        }

        return response()->json($results);
    }
}
