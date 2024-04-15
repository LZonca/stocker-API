<?php

use App\Http\Controllers\Api\GroupeController;
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

Route::post('/users/{user}/groups/{group}', [UserController::class, 'associateUser'])->name('api.user.associateUser');

/**
    Les stocks des utilisateurs
 **/

Route::get('/users/{user}/stocks', [StockController::class, 'index'])->name('api.stock.index');
Route::post('/users/{user}/stocks', [StockController::class, 'store'])->name('api.stock.store');
Route::get('/users/{user}/stocks/{stock}', [StockController::class, 'show'])->name('api.stock.show');
Route::put('/users/{user}/stocks/{stock}', [StockController::class, 'update'])->name('api.stock.update');
Route::delete('/users/{user}/stocks/{stock}', [StockController::class, 'destroy'])->name('api.stock.destroy');

/**
    Les produits des stocks
 **/

Route::post('/users/{user}/{stock}/stocks/produits', [StockController::class, 'addProduct'])->name('api.stock.addProduct');
Route::get('/users/{user}/stocks/{stock}/produits', [StockController::class, 'content'])->name('api.stock.content');


Route::get('/groups', [GroupeController::class, 'index']);
Route::post('/groups', [GroupeController::class, 'store']);
Route::get('/groups/{groupe}', [GroupeController::class, 'show']);
Route::patch('/groups/{groupe}', [GroupeController::class, 'update']);
Route::delete('/groups/{groupe}', [GroupeController::class, 'destroy']);
Route::get('/groups/{groupe}/users', [GroupeController::class, 'users'])->name('api.group.users');
