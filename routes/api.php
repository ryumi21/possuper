<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    Route::apiResource('foods', \App\Http\Controllers\Api\FoodController::class);
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
    Route::apiResource('itemunits', \App\Http\Controllers\Api\ItemUnitController::class);
    Route::apiResource('rawmaterials', \App\Http\Controllers\Api\RawMaterialController::class);
    Route::apiResource('transactions', \App\Http\Controllers\Api\TransactionController::class);
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword']);
    Route::get('rolepermissions', [\App\Http\Controllers\Api\RolePermissionController::class, 'index']);
    Route::get('rolepermissions/{userId}', [\App\Http\Controllers\Api\RolePermissionController::class, 'show']);
    Route::put('rolepermissions/{userId}', [\App\Http\Controllers\Api\RolePermissionController::class, 'update']);
    Route::apiResource('roles', \App\Http\Controllers\Api\RoleController::class);
});
