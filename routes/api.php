<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/events', [EventController::class, 'index'])->name('events.index');
Route::middleware('auth:sanctum')->get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::middleware('auth:sanctum')->delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

Route::middleware('auth:sanctum')->post('/contacts', [ContactController::class, 'addContact'])->name('contacts.add');
Route::middleware('auth:sanctum')->post('/contacts/respond', [ContactController::class, 'respondContact'])->name('contacts.respond');

Route::middleware('auth:sanctum')->post('/task/{task}/complete', [TaskController::class, 'setComplete'])->name('task.complete');
Route::middleware('auth:sanctum')->post('/task/{todo}/add', [TaskController::class, 'store'])->name('task.add');
Route::middleware('auth:sanctum')->delete('/task/{task}', [TaskController::class, 'destroy'])->name('task.delete');
