<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware([RoleMiddleware::class . ':ADMIN,USER'])->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);
});
