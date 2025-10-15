<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\UserDeviceController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::post('payments/webhook', [PaymentController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me',      [AuthController::class, 'me']);

    Route::post('devices/register',   [UserDeviceController::class, 'register']);
    Route::post('devices/unregister', [UserDeviceController::class, 'unregister']);

    Route::apiResource('events', EventController::class) ->only(['index', 'show']);

    Route::get('registrations/user/{userId}', [RegistrationController::class, 'getByUserId']);
    Route::apiResource('registrations', RegistrationController::class) ->only(['index', 'store', 'show',]);
    
    Route::apiResource('payments', PaymentController::class) ->only(['store']);
});