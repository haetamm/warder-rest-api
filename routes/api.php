<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::middleware(['role' . ':ADMIN,USER,SELLER'])->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user/reset-password', [UserController::class, 'changePassword']);
    Route::get('/user/address', [AddressController::class, 'index']);
    Route::post('/user/address', [AddressController::class, 'store']);
    Route::get('/user/address/{id}', [AddressController::class, 'getById']);
    Route::put('/user/address/{id}', [AddressController::class, 'update']);
    Route::delete('/user/address/{id}', [AddressController::class, 'deleteById']);
});

Route::middleware(['role' . ':USER'])->group(function () {
    Route::post('/seller', [SellerController::class, 'store']);
    Route::post('/seller/region', [SellerController::class, 'storeRegion']);
});

Route::middleware(['role' . ':SELLER'])->group(function () {
    Route::get('/seller', [SellerController::class, 'getCurrentSeller']);
    Route::put('/seller', [SellerController::class, 'updateSeller']);
});
