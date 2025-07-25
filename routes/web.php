<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DashboadController as DashboardController;

Route::get('/', function () {
     return view('welcome');
});

Route::get('login', [AdminUserController::class, 'showLoginForm'])->name('login');
Route::post('login', [AdminUserController::class, 'login']);
Route::post('logout', [AdminUserController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'is_admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
          Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
          Route::resource('events', AdminEventController::class)
               ->except(['create', 'store', 'edit', 'update', 'destroy']);
          Route::resource('users', AdminUserController::class);
          Route::resource('registrations', AdminRegistrationController::class)
               ->only(['index', 'show', 'destroy']);
          Route::resource('payments', AdminPaymentController::class)
               ->only(['index', 'show', 'update']);
     });