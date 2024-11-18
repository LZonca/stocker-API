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


        $stocks = $userStocks->unique('id');

        return response()->json($stocks->values());
    }



    public function editProductInUserStock(Request $request, Stock $stock, Produit $product)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
            'image' => 'sometimes|nullable|string',
            'expiry_date' => 'sometimes|nullable|date',
            'prix' => 'sometimes|nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Check if the stock belongs to the user
        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found or does not belong to you.')], 404);
        }

        // Check if a product with the same name and code exists in the stock
        $existingProduct = $stock->produits()
            ->where('nom', $request->nom)
            ->where('code', $request->code)
            ->where('id', '!=', $product->id)
            ->first();

        if ($existingProduct) {
            return response()->json(['message' => __('A product with the same name and code already exists in this stock.')], 422);
        }

        // Update the product with the new data
        $product->update($request->only(['nom', 'code', 'description', 'image', 'expiry_date', 'prix']));

        return response()->json(['message' => __('Product updated successfully.')], 200);
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

        // Check if a stock with the same nom already exists
        $existingStock = Stock::where('nom', $request->nom)->first();
        if ($existingStock) {
            return response()->json(['message' => __('A stock with the same name already exists.')], 422);
        }

        $stock = new Stock($request->all());
        $stock->proprietaire()->associate($request->user());
        $stock->save();

        return response()->json($stock, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Produit $produit)
    {

        return response()->json($produit);
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

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Delete all the associated products
        $stock->produits()->delete();
        $stock->proprietaire()->dissociate();

        // Delete the stock
        $stock->delete();

        return response()->json(['message' => __('Stock removed successfully')], 204);
    }

    public function updateProductQuantity(Request $request, Stock $stock, Produit $product)
    {
        $validator = Validator::make($request->all(), [
            'quantite' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Update the product quantity
       $product->quantite = $request->quantite;

        return response()->json(['message' => __('Product quantity updated successfully.')], 200);
    }


    public function addProduct(Stock $stock, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'expiry_date' => 'nullable|date',
            'prix' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Check if a product with the same name already exists in the stock
        $existingProduct =  $stock->produits()
            ->where('nom', $request->nom)
            ->where('code', $request->code)
            ->first();
        if ($existingProduct) {
            return response()->json(['message' => __('A product with the same name and code already exists in this stock.')], 422);
        }

        $product = Produit::where('nom', $request->nom)->first();

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        // Add the product to the stock
        $product->quantite = 1;


        return response()->json(['message' => __('Product added to the stock successfully.')], 200);
    }

    // StockController.php

    public function showProductInUserStock(Request $request, Stock $stock, Produit $product)
    {
        $stock = $request->user()->stocks->find($stock->id);

        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        $produit = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$produit) {
            return response()->json(['message' => __('Product not found in this stock.')], 404);
        }

        return response()->json($produit);
    }

    public function decreaseProductQuantityInUserStock(Request $request, Stock $stock, Produit $product)
    {
        $stock = $request->user()->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        // Check if the product quantity is greater than 1
        if ($product->quantite > 1) {
            $product->quantite = $product->quantite - 1;
            return response()->json(['message' => __('Product quantity decreased successfully.')], 200);
        } else {
            $product->delete();
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

        return response()->json(['message' => __('Product removed from the stock successfully.')], 200);
    }

    /**
     * Display the products of a user's stock.
     */
    public function content(Request $request, Stock $stock)
    {
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
        }

        return response()->json($stock->produits());
    }
}
