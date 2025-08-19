<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DashboadController as DashboardController;

// Route::get('/', function () {
//      return view('welcome');
// });

Route::get('/', [AdminUserController::class, 'showLoginForm'])->name('login');
Route::post('/', [AdminUserController::class, 'login']);
Route::post('logout', [AdminUserController::class, 'logout'])->name('logout');


Route::middleware(['web', 'is_admin']) 
     ->group(function () {
          Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
          Route::resource('admin/events', \App\Http\Controllers\Admin\EventController::class);
          Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class);
          Route::resource('admin/registrations', \App\Http\Controllers\Admin\RegistrationController::class);
          Route::resource('admin/payments', \App\Http\Controllers\Admin\PaymentController::class);
     });