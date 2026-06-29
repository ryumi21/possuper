<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    Route::apiResource('foods', \App\Http\Controllers\Api\FoodController::class);
    Route::apiResource('itemunits', \App\Http\Controllers\Api\ItemUnitController::class);
    Route::apiResource('rawmaterials', \App\Http\Controllers\Api\RawMaterialController::class);
});
