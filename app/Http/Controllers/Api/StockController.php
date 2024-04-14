<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return response()->json($user->stocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'nom' => 'required|max:255',
        ]);

        $stock = new Stock($request->all());
        $stock->proprietaire()->associate($user);
        $stock->save();

        return response()->json($stock, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, Stock $stock)
    {
        return response()->json($user->stocks->find($stock->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Stock $stock)
    {
        $request->validate([
            'nom' => 'required|max:255',
        ]);

        $stock = $user->stocks->find($stock->id);
        $stock->update($request->all());

        return response()->json($stock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Stock $stock)
    {
        $stock = $user->stocks->find($stock->id);
        $stock->delete();

        return response()->json(null, 204);
    }

    public function addProduct(User $user, Stock $stock, Request $request)
    {
        $stock = $user->stocks->find($stock->id);
        $product = Produit::find($request->id);

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        if ($stock->produits->contains($product->id)) {
            // The product is already in the stock, increment the quantity
            $stock->produits()->updateExistingPivot($product->id, ['quantity' => $product->quantite + 1]);
            return response()->json(['message' => 'Product quantity incremented.'], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id);
            return response()->json(['message' => 'Product added to stock successfully.'], 200);
        }
    }

    /**
     * Display the products of a user's stock.
     */
    public function content(User $user, Stock $stock)
    {
        return response()->json($user->stocks->find($stock->id)->produits);
    }
}
