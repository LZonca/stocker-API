<?php

use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
Route::patch('/users/{user}', [UserController::class, 'update']);
Route::delete('/users/{user}', [UserController::class, 'destroy']);

/**
    Les stocks des utilisateurs
 **/
Route::get('/users/{user}/stocks', [StockController::class, 'index']);
Route::post('/users/{user}/stocks', [StockController::class, 'store']);
Route::get('/users/{user}/stocks/{stock}', [StockController::class, 'show']);
Route::put('/users/{user}/stocks/{stock}', [StockController::class, 'update']);
Route::delete('/users/{user}/stocks/{stock}', [StockController::class, 'destroy']);

/**
    Les produits des stocks
 **/

Route::post('/users/{user}/{stock}/produits/add', [StockController::class, 'addProduct']);
Route::get('/users/{user}/{stock}/produits', [StockController::class, 'content']);
