<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('register', [AuthController::class, 'register'])->middleware(['auth:sanctum', 'permission:user.create']);
Route::post('login', [AuthController::class, 'login']);


Route::get('checkout', [CheckoutController::class, 'index'])->middleware('auth:sanctum');

Route::get('roles', [AuthController::class, 'getRoles'])->middleware(['auth:sanctum', 'permission:role.index']);
