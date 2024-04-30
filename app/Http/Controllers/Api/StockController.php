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

    public function userStocks(Request $request)
    {
        $userStocks = $request->user()->stocks;
        foreach ($userStocks as $stock) {
            $stock->load('produits');
        }

        $groupStocks = $request->user()->groupes->flatMap(function ($group) {
            return $group->stocks;
        });
        foreach ($groupStocks as $stock) {
            $stock->load('produits');
        }

        $stocks = $userStocks->concat($groupStocks)->unique('id');

        return response()->json($stocks->values());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|max:255',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = new Stock($request->all());
        $stock->proprietaire()->associate($request->user());
        $stock->save();

        return response()->json($stock, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Stock $stock)
    {
        return response()->json($request->user()->stocks->find($stock->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|max:255',
            'image' => 'nullable|image',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $request->user()->stocks->find($stock->id);
        $stock->update($request->all());

        return response()->json($stock);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Stock $stock)
    {
        $stock = $request->user()->stocks->find($stock->id);
        $stock->delete();

        return response()->json(['message' => __('Stock removed successfully')], 204);
    }

    public function addProduct(Stock $stock, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'image' => 'nullable|image',
            // Ensure that 'nom' is always provided
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $request->user()->stocks->find($stock->id);


        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        $product = Produit::where('code', $request->code)->first();

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        // Check if the product's code is already in the stock
        $pivot = $stock->produits()->where('nom', $product->nom)->first();

        if ($pivot) {
            // The product is already in the stock, increment the quantity
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite + 1')]);
            return response()->json(['message' => __('Product quantity incremented.')], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id, ['quantite' => 1]);
            return response()->json(['message' => __('Product added to the stock successfully.')], 200);
        }
    }

    public function decreaseProductQuantityInUserStock(Request $request, Stock $stock, Produit $product)
    {
        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$pivot) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Check if the product quantity is greater than 1
        if ($pivot->quantite > 1) {
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite - 1')]);
            return response()->json(['message' => __('Product quantity decreased successfully.')], 200);
        } else {
            $stock->produits()->detach($product->id);
            return response()->json(['message' => __('Product removed from the stock successfully.')], 200);
        }
    }

    public function removeProductFromUserStock(Request $request, Stock $stock, Produit $product)
    {
        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$pivot) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        $stock->produits()->detach($product->id);

        return response()->json(['message' => __('Product removed from the stock successfully.')], 200);
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
