<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

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
Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('/task', [TaskController::class, 'index']);
    Route::get('/task', [TaskController::class, 'getAll']);
    Route::post('/task/create', [TaskController::class, 'create']);
    Route::post('/task/show', [TaskController::class, 'show']);
    Route::patch('/task', [TaskController::class, 'edit']);
    Route::put('/task', [TaskController::class, 'update']);
    Route::delete('/task', [TaskController::class, 'destroy']);
});
