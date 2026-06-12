<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
Route::apiResource('foods', \App\Http\Controllers\Api\FoodController::class);
Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
Route::post('users/{user}/reset-password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword']);
