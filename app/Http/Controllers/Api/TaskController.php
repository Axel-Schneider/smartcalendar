<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\ToDo;
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

    public function store(Request $request)
    {
        if(
            $request->description == null || 
            $request->todo_id == null ||
            !Todo::where('id', $request->todo_id)->exists()            
            ) {
            return response()->json(['error' => 'Bad Request'], 400);
        }
        if(ToDo::where('id', $request->todo_id)->first()->event->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task = new Task();
        $task->description = $request->description;
        $task->complete = 0;
        $task->todo_id = $request->todo_id;
        $task->save();
 
        return response()->json([
            'success' => true,
            'task' => $task
        ]);
    }
}
