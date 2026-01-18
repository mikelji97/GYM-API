<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GymClassController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    //Gym class
    Route::get('/gym-classes', [GymClassController::class, 'index']);
    Route::post('/gym-classes', [GymClassController::class, 'store']);
    Route::get('/gym-classes/{id}', [GymClassController::class, 'show']);
    Route::put('/gym-classes/{id}', [GymClassController::class, 'update']);
    Route::delete('/gym-classes/{id}', [GymClassController::class, 'destroy']);
    //Gym sessions
    Route::get('/sessions', [SessionController::class, 'index']);
    Route::get('/sessions/available', [SessionController::class, 'available']);
    Route::get('/sessions/{id}', [SessionController::class, 'show']);
    Route::post('/sessions', [SessionController::class, 'store']);
    Route::put('/sessions/{id}', [SessionController::class, 'update']);
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy']);
    //Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/{id}/stats', [UserController::class, 'stats']);
    //bookings
    Route::get('/bookings', [BookingController::class, 'index']);  

});


