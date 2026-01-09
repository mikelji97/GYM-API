<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GymClassController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/gym-classes', [GymClassController::class, 'index']);
    Route::post('/gym-classes', [GymClassController::class, 'store']);
    Route::get('/gym-classes/{id}', [GymClassController::class, 'show']);
    Route::put('/gym-classes/{id}', [GymClassController::class, 'update']);
    Route::delete('/gym-classes/{id}', [GymClassController::class, 'destroy']);
});
