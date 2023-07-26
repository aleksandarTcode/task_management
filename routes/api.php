<?php

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/tasks', function(){
    return Task::all();
});

Route::post('/tasks', function(){
    return Task::create([
        'title' => "Task one",
        'description' => "Task one description",
        'status' => "Task one status",
        'priority' => "Task one priority",
        'due_date' => "2023-08-10 01:53:14",
        'creator_id' => 1,
        'assigned_user_id'=> 2,


    ]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
