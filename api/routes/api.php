<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;




//USER

Route::post('/user/register', [UserController::class, 'store']);

Route::post('/user/login', [UserController::class, 'login']);

Route::middleware([EnsureFrontendRequestsAreStateful::class, 'auth:sanctum'])->group(function () {

Route::get('/user', [UserController::class, 'index']);

Route::get('/users', [UserController::class, 'getAllUsers']);

Route::get('/user/{id}', [UserController::class, 'show']);

Route::put('/user/{id}', [UserController::class, 'update']);

Route::post('/user/logout', [UserController::class, 'logout']);

Route::delete('/user/{id}', [UserController::class, 'destroy']);

//TASK

Route::get('/tasks', [TaskController::class, 'index']);

Route::get('/tasks/all', [TaskController::class, 'allTasks']);

Route::post('/tasks', [TaskController::class, 'store']);

Route::get('/tasks/{id}', [TaskController::class, 'show']);

Route::put('/tasks/{id}/toggle-completion', [TaskController::class, 'toggleCompletion']);

Route::put('/tasks/{id}', [TaskController::class, 'update']);

Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

});