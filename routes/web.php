<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);

Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::post('/messages/check-status', [MessageController::class, 'checkStatus']);