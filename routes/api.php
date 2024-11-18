<?php

use App\Http\Controllers\Api\GroupeController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\api\CheckGroupAccess;
use App\Http\Middleware\api\CheckGroupOwnership;
use App\Http\Middleware\api\CheckUserSelf;
use App\Http\Middleware\api\SetLocale;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/version', [UserController::class, 'getVersion'])->name('api.version');


Route::middleware([SetLocale::class])->group(function () {
    // Routes d'authentification
    Route::post('/login', [UserController::class, 'login'])->name('api_login'); // Connexion de l'utilisateur
    Route::post('/register', [UserController::class, 'register'])->name('api_login'); // Inscription de l'utilisateur



// Groupe de routes nécessitant une authentification
    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('/email/verification-notification', [UserController::class, 'sendVerificationEmail'])
            ->name('api.email.verification-notification');

        // Obtenir l'utilisateur authentifié
        Route::get('/user', function (Request $request) {
            $request->user()->load('stocks.produits', 'groupes.stocks.produits', 'groupes.members', 'groupes.proprietaire');
            return response()->json( $request->user());
        });

// Routes utilisateurs
        /*Route::get('/users', [UserController::class, 'index']); // Obtenir tous les utilisateurs*/

        Route::middleware([CheckUserSelf::class])->group(function () {
            Route::patch('/user', [UserController::class, 'update']); // Mettre à jour un utilisateur spécifique
            Route::delete('/users/{user}', [UserController::class, 'destroy']); // Supprimer un utilisateur spécifique
            Route::get('/user/groups', [UserController::class, 'groups']); // Obtenir tous les groupes d'un utilisateur spécifique
            Route::post('/user/groups', [GroupeController::class, 'store']); // Créer un nouveau groupe au nom de l'utilisateur
            Route::patch('/user/groups/{groupe}/leave', [UserController::class, 'leaveGroup']);  // Obtenir un groupe spécifique

            // Routes de stock
            Route::get('/user/stocks', [StockController::class, 'userStocks'])->name('api.stock.userStocks  '); // Obtenir tous les stocks d'un utilisateur spécifique
            Route::get('/user/stocks/{stock}/produits/{product}', [StockController::class, 'showProductInUserStock'])->name('api.user.stock.showProduct');
            /*Route::get('/users/{user}/stocks/all', [StockController::class, 'userStocks'])->name('api.stock.userStocks'); // Obtenir tous les stocks d'un utilisateur spécifique*/
            Route::post('/user/stocks', [StockController::class, 'store'])->name('api.stock.store'); // Créer un nouveau stock pour un utilisateur spécifique
            Route::get('/user/stocks/{stock}', [StockController::class, 'show'])->name('api.stock.show'); // Obtenir un stock spécifique d'un utilisateur spécifique
            Route::put('/user/stocks/{stock}', [StockController::class, 'update'])->name('api.stock.update'); // Mettre à jour un stock spécifique d'un utilisateur spécifique
            Route::delete('/user/stocks/{stock}', [StockController::class, 'destroy'])->name('api.stock.destroy'); // Supprimer un stock spécifique d'un utilisateur spécifique

            Route::get('/user/stocks/{stock}/lists', [ShoppingList::class, 'shoppingLists'])->name('api.stock.shoppingLists'); // Obtenir toutes les listes de courses d'un stock spécifique d'un utilisateur
            Route::post('/user/stocks/{stock}/lists', [ShoppingList::class, 'addShoppingList'])->name('api.stock.addShoppingList'); // Ajouter une liste de courses à un stock spécifique d'un utilisateur
            Route::get('/user/stocks/{stock}/lists/{list}', [ShoppingList::class, 'showShoppingList'])->name('api.stock.showShoppingList'); // Obtenir une liste de courses spécifique d'un stock spécifique d'un utilisateur
            Route::patch('/user/stocks/{stock}/lists/{list}', [ShoppingList::class, 'editShoppingList'])->name('api.stock.editShoppingList'); // Mettre à jour une liste de courses spécifique d'un stock spécifique d'un utilisateur
            Route::delete('/user/stocks/{stock}/lists/{list}', [ShoppingList::class, 'deleteShoppingList'])->name('api.stock.deleteShoppingList'); // Supprimer une liste de courses spécifique d'un stock spécifique d'un utilisateur
            Route::patch('/user/stocks/{stock}/lists/{list}/products', [ShoppingList::class, 'addProductToShoppingList'])->name('api.stock.addProductToShoppingList'); // Ajouter un produit à une liste de courses spécifique d'un stock spécifique d'un utilisateur
            Route::delete('/user/stocks/{stock}/lists/{list}/products/{product}', [ShoppingList::class, 'removeProductFromShoppingList'])->name('api.stock.removeProductFromShoppingList'); // Retirer un produit d'une liste de courses spécifique d'un stock spécifique d'un utilisateur

            // Routes produits
            Route::get('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'showProductInGroupStock'])->name('api.group.stock.showProduct');
            Route::post('/user/stocks/{stock}/produits', [StockController::class, 'addProduct'])->name('api.stock.addProduct'); // Ajouter un produit à un stock spécifique d'un utilisateur spécifique
            Route::patch('/user/stocks/{stock}/produits/{product}', [StockController::class, 'editProductInUserStock'])->name('api.user.stock.editProduct');
            Route::get('/user/stocks/{stock}/produits', [StockController::class, 'content'])->name('api.stock.content'); // Obtenir tous les produits d'un stock spécifique d'un utilisateur spécifique
            Route::delete('/user/stocks/{stock}/produits/{product}', [StockController::class, 'removeProductFromUserStock'])->name('api.user.stock.removeProduct'); // Supprimer un produit d'un stock spécifique d'un utilisateur spécifique
            Route::patch('/user/stocks/{stock}/produits/{product}/quantite', [StockController::class, 'updateProductQuantity'])->name('api.user.stock.updateProductQuantity'); // Diminuer la quantité d'un produit spécifique dans un stock spécifique d'un utilisateur spécifique
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
        Route::middleware([CheckGroupAccess::class])->group(function () {

            Route::get('/groups/{groupe}', [GroupeController::class, 'show']); // Obtenir un groupe spécifique

            Route::middleware([CheckGroupOwnership::class])->group(function () {
                Route::patch('/groups/{groupe}', [GroupeController::class, 'update']); // Mettre à jour un groupe spécifique
                Route::delete('/groups/{groupe}', [GroupeController::class, 'destroy']); // Supprimer un groupe spécifique
                Route::post('/groups/{groupe}/add', [GroupeController::class, 'associateUser'])->name('api.user.associateUser'); // Associer un utilisateur à un groupe
                Route::patch('/groups/{groupe}/users/{user}', [GroupeController::class, 'dissociateUser'])->name('api.user.dissociateUser'); // Dissocier un utilisateur d'un groupe
                Route::patch('/groups/{groupe}/stocks/{stock}', [GroupeController::class, 'updateGroupStock'])->name('api.group.updateStock');
                Route::delete('/groups/{groupe}/stocks/{stock}', [GroupeController::class, 'removeStockFromGroup'])->name('api.group.removeStock'); // Supprimer un stock d'un groupe
            });

            Route::get('/groups/{groupe}/users', [GroupeController::class, 'users'])->name('api.group.users'); // Obtenir tous les utilisateurs d'un groupe spécifique
            Route::get('/groups/{groupe}/users/{user}', [GroupeController::class, 'user'])->name('api.group.user'); // Obtenir un utilisateur spécifique d'un groupe spécifique

            // Routes stock de groupes
            Route::get('/groups/{groupe}/stocks', [GroupeController::class, 'groupStocks'])->name('api.group.stocks'); // Obtenir tous les stocks d'un groupe spécifique
            Route::post('/groups/{groupe}/stocks', [GroupeController::class, 'addStockToGroup'])->name('api.group.addStockToGroup'); // Ajouter un stock à un groupe
            Route::get('/groups/{groupe}/stocks/{stock}', [GroupeController::class, 'groupStock'])->name('api.group.stock'); // Obtenir un stock spécifique d'un groupe spécifique
            Route::get('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'groupStockProducts'])->name('api.group.stock.products'); // Obtenir tous les produits d'un stock spécifique d'un groupe spécifique
/*            Route::get('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'groupStockProduct'])->name('api.group.stock.product'); // Obtenir un produit spécifique d'un stock spécifique d'un groupe spécifique*/
            Route::patch('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'editProductInGroupeStock'])->name('api.group.stock.update'); // Mettre à jour un stock spécifique d'un groupe spécifique
            Route::post('/groups/{groupe}/stocks/{stock}/produits', [GroupeController::class, 'addProduct'])->name('api.group.stock.addProduct'); // Ajouter un produit à un stock spécifique d'un groupe spécifique
            Route::delete('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'removeProductFromGroupStock'])->name('api.group.stock.removeProduct'); // Supprimer un produit d'un stock spécifique d'un groupe spécifique
            Route::patch('/groups/{groupe}/stocks/{stock}/produits/{product}', [GroupeController::class, 'editProductInGroupeStock'])->name('api.group.stock.removeProduct'); // Supprimer un produit d'un stock spécifique d'un groupe spécifique
            Route::patch('/groups/{groupe}/stocks/{stock}/produits/{product}/quantite', [GroupeController::class, 'updateProductQuantity'])->name('api.group.stock.updateProductQuantity'); // Diminuer la quantité d'un produit spécifique dans un stock spécifique d'un groupe spécifique

        });
    });
});


