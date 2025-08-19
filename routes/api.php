<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\PaymentController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('events', EventController::class) ->only(['index', 'show']);
    Route::apiResource('registrations', RegistrationController::class) ->only(['store']);
    Route::apiResource('payments', PaymentController::class) ->only(['store']);
});

Route::post('/payments/webhook', [PaymentController::class, 'webhook']);