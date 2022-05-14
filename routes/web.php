<?php

use App\Http\Controllers\EventController;
use App\http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->middleware(['auth'])->name('home');

Route::post('/events', [EventController::class, 'store'])->middleware(['auth'])->name('events.store');
Route::post('/events/{event}', [EventController::class, 'update'])->middleware(['auth'])->name('events.update');

Route::get('/test', function () {
    return view('test');
});
require __DIR__.'/auth.php';
