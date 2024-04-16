<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:255',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

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
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:255',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }



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

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'nom' => 'required', // Ensure that 'nom' is always provided
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $user->stocks->find($stock->id);


        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => 'Stock not found.'], 404);
        }

        $product = Produit::where('code', $request->code)->first();

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        // Check if the product's code is already in the stock
        $pivot = $stock->produits()->where('code', $product->code)->first();

        if ($pivot) {
            // The product is already in the stock, increment the quantity
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite + 1')]);
            return response()->json(['message' => 'Product quantity incremented.'], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id, ['quantite' => 1]);
            return response()->json(['message' => 'Product added to stock successfully.'], 200);
        }
    }

    public function decreaseProductQuantityInUserStock(User $user, Stock $stock, Produit $product)
    {
        $stock = $user->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => 'Stock not found.'], 404);
        }

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$pivot) {
            return response()->json(['message' => 'Product not found in the stock.'], 404);
        }

        // Check if the product quantity is greater than 1
        if ($pivot->quantite > 1) {
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite - 1')]);
            return response()->json(['message' => 'Product quantity decremented.'], 200);
        } else {
            $stock->produits()->detach($product->id);
            return response()->json(['message' => 'Product removed from stock successfully.'], 200);
        }
    }

    public function removeProductFromUserStock(User $user, Stock $stock, Produit $product)
    {
        $stock = $user->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => 'Stock not found.'], 404);
        }

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$pivot) {
            return response()->json(['message' => 'Product not found in the stock.'], 404);
        }

        $stock->produits()->detach($product->id);

        return response()->json(['message' => 'Product removed from stock successfully.'], 200);
    }

    /**
     * Display the products of a user's stock.
     */
    public function content(User $user, Stock $stock)
    {
        $stock = $user->stocks->find($stock->id);
        $products = $stock->produits->map(function ($product) {
            return [
                'id' => $product->id,
                'code' => $product->code,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix' => $product->prix,
                'image' => $product->image,
                'categorie_id' => $product->categorie_id,
                'quantite' => $product->pivot->quantite,
            ];
        });

        return response()->json($products);
    }
}
