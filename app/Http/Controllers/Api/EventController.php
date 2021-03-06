<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        function asPower(Event $event)
        {
            if ($event->owner->id == Auth::user()->id) return true;
            if ($event->commonWith()->where('user_id', Auth::user()->id)->exists()) return true;
            return false;
        }
        $start = $request->query('start');
        $end = $request->query('end');

        $events = Event::where('user_id', '=', Auth::user()->id)
            ->where(function ($query) use ($start, $end) {
                $query->orWhere(function ($query) use ($start, $end) {
                    $query->where('startDate', '>=', $start)
                        ->where('startDate', '<=', $end);
                })->orWhere(function ($query) use ($start, $end) {
                    $query->where('endDate', '>=', $start)
                        ->where('endDate', '<=', $end);
                })->orWhere(function ($query) use ($start, $end) {
                    $query->where('startDate', '<=', $start)
                        ->where('endDate', '>=', $end);
                });
            })->get();

        $sharedEvents = Auth::user()->shareds()->where(function ($query) use ($start, $end) {
            $query->orWhere(function ($query) use ($start, $end) {
                $query->where('startDate', '>=', $start)
                    ->where('startDate', '<=', $end);
            })->orWhere(function ($query) use ($start, $end) {
                $query->where('endDate', '>=', $start)
                    ->where('endDate', '<=', $end);
            })->orWhere(function ($query) use ($start, $end) {
                $query->where('startDate', '<=', $start)
                    ->where('endDate', '>=', $end);
            });
        })->get();

        $events = $events->merge($sharedEvents);

        $results = [];
        foreach ($events as $event) {
            $format = $event->fullDay ? 'Y-m-d' : 'c';
            $owner = $event->owner;
            $results[] = [
                "id" => $event->id,
                "title" => $event->title,
                "start" => $event->startDate()->format($format),
                "end" => $event->endDate()->format($format),
                "fullDay" => $event->fullDay,
                "recurring" => $event->recurring,
                "description" => $event->description,
                "sharedWith" => (asPower($event)) ? $event->sharedWith->pluck('name', 'id')->toArray() : [],
                "commonWith" => (asPower($event)) ? $event->commonWith()->where('user_id', '!=', Auth::user()->id)->get()->pluck('name', 'id')->toArray() : [],
                "asPower" => asPower($event),
                "owner" => ($owner->id != Auth::user()->id) ? $owner->name : null,
                "color" => ($owner->id != Auth::user()->id) ? (($owner->contact(Auth::user()) != null) ? $owner->contact(Auth::user())->color() : "") : "",
                "classNames" => ($owner->id != Auth::user()->id) ? [
                        "user-" . $owner->id,
                    ] : [],
                "todo_id" => $event->todo_id,
                "todo" => $event->todo != null ? $event->todo->tasks->map(function ($task) {
                    return $task->getFills();
                }) : null,
            ];
        }

        return response()->json($results);
    }

    public function show(Event $event)
    {
        if ($event->user_id != Auth::user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if ($event->user_id == Auth::user()->id) {
            $event->delete();
        } else {
            abort(403, 'Unauthorized action.');
        }
        return response()->json(['success' => 'Event deleted']);
    }
}
