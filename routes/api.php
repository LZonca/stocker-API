<?php

use App\Http\Controllers\Api\GroupeController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckStockAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'store'])->name('register');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::patch('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/users/{user}/groups', [UserController::class, 'groups']);



    /**
        Les stocks des utilisateurs
     **/

/*    Route::middleware([CheckStockAccess::class])->group(function () {*/
        Route::get('/users/{user}/stocks', [StockController::class, 'index'])->name('api.stock.index');
        Route::post('/users/{user}/stocks', [StockController::class, 'store'])->name('api.stock.store');
        Route::get('/users/{user}/stocks/{stock}', [StockController::class, 'show'])->name('api.stock.show');
        Route::put('/users/{user}/stocks/{stock}', [StockController::class, 'update'])->name('api.stock.update');
        Route::delete('/users/{user}/stocks/{stock}', [StockController::class, 'destroy'])->name('api.stock.destroy');
/*    });*/


    /**
        Les produits des stocks des utilisateurs
     **/

    Route::post('/users/{user}/stocks/{stock}/produits', [StockController::class, 'addProduct'])->name('api.stock.addProduct');
    Route::get('/users/{user}/stocks/{stock}/produits', [StockController::class, 'content'])->name('api.stock.content');
    Route::delete('/users/{user}/stocks/{stock}/produits/{product}', [StockController::class, 'removeProductFromUserStock'])->name('api.user.stock.removeProduct');
    Route::patch('/users/{user}/stocks/{stock}/produits/{product}/remove', [StockController::class, 'decreaseProductQuantityInUserStock'])->name('api.user.stock.decreaseProductQuantity');

    /**
        Les groupes
     **/

    Route::get('/groups', [GroupeController::class, 'index']);
    Route::post('/groups', [GroupeController::class, 'store']);
    Route::get('/groups/{groupe}', [GroupeController::class, 'show']);
    Route::patch('/groups/{groupe}', [GroupeController::class, 'update']);
    Route::delete('/groups/{groupe}', [GroupeController::class, 'destroy']);
    Route::get('/groups/{groupe}/users', [GroupeController::class, 'users'])->name('api.group.users');
    Route::post('/users/{user}/groups/{group}', [GroupeController::class, 'associateUser'])->name('api.user.associateUser');
    Route::delete('/users/{user}/groups/{group}', [GroupeController::class, 'dissociateUser'])->name('api.user.dissociateUser');


    /**
        Les stocks des groupes
     **/

    Route::get('/groups/{groupe}/stocks', [GroupeController::class, 'groupStocks'])->name('api.group.stocks');
    Route::post('/groups/{groupe}/stocks/{stock}', [GroupeController::class, 'addStockToGroup'])->name('api.group.addStockToGroup');
    Route::get('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'groupStockProducts'])->name('api.group.stock.products');
    Route::post('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'addProduct'])->name('api.group.stock.addProduct');
    Route::delete('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'removeProductFromGroupStock'])->name('api.group.stock.removeProduct');
    Route::patch('/groups/{groupe}/stocks/{stock}/produits/{product}/remove', [GroupeController::class, 'decreaseProductQuantityInGroupStock'])->name('api.group.stock.decreaseProductQuantity');


});
