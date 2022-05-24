<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function setComplete(Request $request, Task $task)
    {
        if($task->list->event->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if($request->complete != true && $request->complete != false) {
            return response()->json(['error' => 'Bad Request'], 400);
        }

        $task->complete = $request->complete ? 1 : 0;
        $task->save();

        return response()->json([
            'success' => true,
        ]);
    }
}
