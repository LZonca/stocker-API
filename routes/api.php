<?php

use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\GroupeController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckStockAccess;
use App\Http\Middleware\CheckUserSelf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

// Routes d'authentification
Route::post('/login', [UserController::class, 'login'])->name('login'); // Connexion de l'utilisateur
Route::post('/register', [UserController::class, 'store'])->name('register'); // Inscription de l'utilisateur

// Groupe de routes nécessitant une authentification
Route::group(['middleware' => 'auth:sanctum'], function () {

    // Obtenir l'utilisateur authentifié
    Route::get('/user', function (Request $request) {
        return response()->json($request->user()->load('stocks.produits', 'groupes.stocks.produits'));
    });

// Routes utilisateurs
    /*Route::get('/users', [UserController::class, 'index']); // Obtenir tous les utilisateurs*/



    Route::middleware(['user-mgroup'])->group(function () {
        Route::patch('/user', [UserController::class, 'update']); // Mettre à jour un utilisateur spécifique
        Route::delete('/users/{user}', [UserController::class, 'destroy']); // Supprimer un utilisateur spécifique
        Route::get('/user/groups', [UserController::class, 'groups']); // Obtenir tous les groupes d'un utilisateur spécifique

        // Routes de stock
        Route::get('/user/stocks', [StockController::class, 'index'])->name('api.stock.index'); // Obtenir tous les stocks d'un utilisateur spécifique
        /*Route::get('/users/{user}/stocks/all', [StockController::class, 'userStocks'])->name('api.stock.userStocks'); // Obtenir tous les stocks d'un utilisateur spécifique*/
        Route::post('/user/stocks', [StockController::class, 'store'])->name('api.stock.store'); // Créer un nouveau stock pour un utilisateur spécifique
        Route::get('/user/stocks/{stock}', [StockController::class, 'show'])->name('api.stock.show'); // Obtenir un stock spécifique d'un utilisateur spécifique
        Route::put('/users/stocks/{stock}', [StockController::class, 'update'])->name('api.stock.update'); // Mettre à jour un stock spécifique d'un utilisateur spécifique
        Route::delete('/user/stocks/{stock}', [StockController::class, 'destroy'])->name('api.stock.destroy'); // Supprimer un stock spécifique d'un utilisateur spécifique

        // Routes produits
        Route::post('/user/stocks/{stock}/produits', [StockController::class, 'addProduct'])->name('api.stock.addProduct'); // Ajouter un produit à un stock spécifique d'un utilisateur spécifique
        Route::get('/user/stocks/{stock}/produits', [StockController::class, 'content'])->name('api.stock.content'); // Obtenir tous les produits d'un stock spécifique d'un utilisateur spécifique
        Route::delete('/user/stocks/{stock}/produits/{product}', [StockController::class, 'removeProductFromUserStock'])->name('api.user.stock.removeProduct'); // Supprimer un produit d'un stock spécifique d'un utilisateur spécifique
        Route::patch('/user/stocks/{stock}/produits/{product}/remove', [StockController::class, 'decreaseProductQuantityInUserStock'])->name('api.user.stock.decreaseProductQuantity'); // Diminuer la quantité d'un produit spécifique dans un stock spécifique d'un utilisateur spécifique
    });

// Routes catégories

   /*
    Route::get('/categories', [CategorieController::class, 'index']); // Obtenir toutes les catégories
    Route::post('/categories', [CategorieController::class, 'store']); // Créer une nouvelle catégorie
    Route::get('/categories/{categorie}', [CategorieController::class, 'show']); // Obtenir une catégorie spécifique
    Route::put('/categories/{categorie}', [CategorieController::class, 'update']); // Mettre à jour une catégorie spécifique
    Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy']); // Supprimer une catégorie spécifique
    Route::put('/produit/{produit}/categorie/{categorie}', [CategorieController::class, 'linkToCategorie']); // Lier un produit à une catégorie
    Route::put('/produit/{produit}/categorie/{categorie}/unlink', [CategorieController::class, 'unlinkFromCategorie']); // Dissocier un produit d'une catégorie
   */

    /*Route::get('/groups', [GroupeController::class, 'index']); // Obtenir tous les groupes*/
    // Routes groupes
    Route::middleware(['group-mgroup'])->group(function () {
        Route::post('/groups', [GroupeController::class, 'store']); // Créer un nouveau groupe
        Route::get('/groups/{groupe}', [GroupeController::class, 'show']); // Obtenir un groupe spécifique

        Route::middleware(['owner-mgroup'])->group(function () {
            Route::patch('/groups/{groupe}', [GroupeController::class, 'update']); // Mettre à jour un groupe spécifique
            Route::delete('/groups/{groupe}', [GroupeController::class, 'destroy']); // Supprimer un groupe spécifique
            Route::post('groups/{groupe}/users/{user}', [GroupeController::class, 'associateUser'])->name('api.user.associateUser'); // Associer un utilisateur à un groupe
            Route::delete('/groups/{groupe}/users/{user}', [GroupeController::class, 'dissociateUser'])->name('api.user.dissociateUser'); // Dissocier un utilisateur d'un groupe
        });

        Route::get('/groups/{groupe}/users', [GroupeController::class, 'users'])->name('api.group.users'); // Obtenir tous les utilisateurs d'un groupe spécifique
        Route::get('/groups/{groupe}/users/{user}', [GroupeController::class, 'user'])->name('api.group.user'); // Obtenir un utilisateur spécifique d'un groupe spécifique

    // Routes stock de groupes
        Route::get('/groups/{groupe}/stocks', [GroupeController::class, 'groupStocks'])->name('api.group.stocks'); // Obtenir tous les stocks d'un groupe spécifique
        Route::post('/groups/{groupe}/stocks/{stock}', [GroupeController::class, 'addStockToGroup'])->name('api.group.addStockToGroup'); // Ajouter un stock à un groupe
        Route::get('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'groupStockProducts'])->name('api.group.stock.products'); // Obtenir tous les produits d'un stock spécifique d'un groupe spécifique
        Route::post('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'addProduct'])->name('api.group.stock.addProduct'); // Ajouter un produit à un stock spécifique d'un groupe spécifique
        Route::delete('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'removeProductFromGroupStock'])->name('api.group.stock.removeProduct'); // Supprimer un produit d'un stock spécifique d'un groupe spécifique
        Route::patch('/groups/{groupe}/stocks/{stock}/produits/{product}/remove', [GroupeController::class, 'decreaseProductQuantityInGroupStock'])->name('api.group.stock.decreaseProductQuantity'); // Diminuer la quantité d'un produit spécifique dans un stock spécifique d'un groupe spécifique
    });
});
