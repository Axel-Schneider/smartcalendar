<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\ToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function setComplete(Request $request, Task $task)
    {
        if(!Auth::user()->hasPower($task->list->event)) {
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
        if(!Auth::user()->hasPower(ToDo::where('id', $request->todo_id)->first()->event)) {
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

    public function destroy(Task $task)
    {
        if(!Auth::user()->hasPower($task->list->event)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
