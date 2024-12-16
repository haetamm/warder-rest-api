<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['role' . ':ADMIN,USER,SELLER'])->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);
});
