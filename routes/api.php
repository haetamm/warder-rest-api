<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('login/google', [AuthController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/products/{domain}', [ProductController::class, 'getByDomainSeller']);

Route::middleware(['role' . ':ADMIN,USER,SELLER'])->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'index']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/user/reset-password', [UserController::class, 'changePassword']);

    Route::get('/address', [AddressController::class, 'index']);
    Route::post('/address', [AddressController::class, 'store']);
    Route::get('/address/{id}', [AddressController::class, 'getById']);
    Route::put('/address/{id}', [AddressController::class, 'update']);
    Route::delete('/address/{id}', [AddressController::class, 'deleteById']);
});

Route::middleware(['role' . ':USER'])->group(function () {
    Route::post('/seller', [SellerController::class, 'store']);
    Route::post('/seller/region', [SellerController::class, 'storeRegion']);
});

Route::middleware(['role' . ':SELLER'])->group(function () {
    Route::get('/seller', [SellerController::class, 'getCurrentSeller']);
    Route::put('/seller', [SellerController::class, 'updateSeller']);

    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'delete']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'updateById']);
    Route::delete('/products/{id}', [ProductController::class, 'deleteById']);

    Route::get('/my-products', [ProductController::class, 'getByCurrentSeller']);
    Route::put('/my-products/{id}', [ProductController::class, 'updateStatusProductById']);
});
