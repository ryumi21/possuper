<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemUnitController;
use App\Http\Controllers\RawMaterialController;

// Route untuk Guest (Belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pos', [\App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/data', [\App\Http\Controllers\PosController::class, 'data'])->name('pos.data');
    Route::get('/products/{product}/barcode', [ProductController::class, 'barcode'])->name('products.barcode');
    Route::resource('products', ProductController::class);
    Route::resource('foods', \App\Http\Controllers\FoodController::class);
    Route::resource('itemunits', ItemUnitController::class)->only(['index']);
    Route::resource('rawmaterials', RawMaterialController::class)->only(['index']);
    Route::resource('transactions', \App\Http\Controllers\TransactionController::class)->only(['index', 'destroy']);
    Route::get('/kitchen', [\App\Http\Controllers\KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/kitchen/{id}/serve', [\App\Http\Controllers\KitchenController::class, 'serve'])->name('kitchen.serve');
    Route::post('/kitchen/{id}/complete', [\App\Http\Controllers\KitchenController::class, 'complete'])->name('kitchen.complete');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::resource('users', UserController::class)->except(['create', 'edit']);
    Route::get('/rolepermissions', [\App\Http\Controllers\RolePermissionController::class, 'index'])->name('rolepermissions.index');
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->only(['index']);

    // API Routes under Web session authentication
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('rolepermissions', [\App\Http\Controllers\Api\RolePermissionController::class, 'index']);
        Route::get('rolepermissions/{userId}', [\App\Http\Controllers\Api\RolePermissionController::class, 'show']);
        Route::put('rolepermissions/{userId}', [\App\Http\Controllers\Api\RolePermissionController::class, 'update']);
        Route::apiResource('roles', \App\Http\Controllers\Api\RoleController::class);
        Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Api\UserController::class, 'resetPassword']);
        Route::apiResource('transactions', \App\Http\Controllers\Api\TransactionController::class);
    });

    // Config API (Called on every module)
    Route::get('/api/config', [\App\Http\Controllers\Api\ConfigController::class, 'index']);
});
