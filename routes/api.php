<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('error', function () {
        return response()->json(['message' => 'Unauthorized, Invalid access token!'], 401);
    })->name('error');
});


Route::group([
    'prefix' => 'events'
], function () {
    Route::group([
        'middleware' => 'auth:api',
        'prefix' => 'draw'
    ], function () {
        Route::get('/{id?}', [EventController::class, 'index']);
        Route::post('/', [EventController::class, 'draw']);
        Route::post('/update', [EventController::class, 'update']);

    });
});