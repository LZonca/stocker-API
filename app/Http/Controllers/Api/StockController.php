<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserProduit;
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
            foreach ($stock->produits as $produit) {
                $userProduit = UserProduit::where('user_id', $request->user()->id)
                    ->where('produit_id', $produit->id)
                    ->first();

                // If user-specific information exists, use it to override the product details
                if ($userProduit) {
                    $produit->nom = $userProduit->custom_name ?? $produit->nom;
                    $produit->code = $userProduit->custom_code ?? $produit->code;
                    $produit->description = $userProduit->custom_description ?? $produit->description;
                    $produit->image = $userProduit->custom_image ?? $produit->image;
                }
            }
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

        // Check if a product with the same name already exists in the stock
        $existingProduct = $stock->produits()->where('nom', $request->nom)->first();
        if ($existingProduct && $existingProduct->id != $product->id) {
            return response()->json(['message' => __('A product with the same name already exists in this stock.')], 422);
        }

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();
        if (!$pivot) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Find the UserProduit entry for the given user and product
        $userProduit = UserProduit::where('user_id', $request->user()->id)
            ->where('produit_id', $product->id)
            ->first();

        // Update the UserProduit entry with the new product details
        $userProduit->custom_name = $request->nom ?? $userProduit->custom_name;
        $userProduit->custom_description = $request->description ?? $userProduit->custom_description;
        $userProduit->custom_code = $request->code ?? $userProduit->custom_code;
        $userProduit->custom_image = $request->image ?? $userProduit->custom_image;
        $userProduit->save();

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

        $stock = new Stock($request->all());
        $stock->proprietaire()->associate($request->user());
        $stock->save();

        return response()->json($stock, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Produit $produit)
    {
        // Fetch the user-specific information for the product
        $userProduit = UserProduit::where('user_id', $request->user()->id)
            ->where('produit_id', $produit->id)
            ->first();

        // If user-specific information exists, use it to override the product details
        if ($userProduit) {
            $produit->nom = $userProduit->custom_name ?? $produit->nom;
            $produit->description = $userProduit->custom_description ?? $produit->description;
            $userProduit->custom_code = $request->code ?? $userProduit->custom_code;
            $produit->image = $userProduit->custom_image ?? $produit->image;
        }

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

        // Detach all the associated products
        $stock->produits()->detach();
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

        // Check if the product is in the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();

        if (!$pivot) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Update the product quantity
        $stock->produits()->updateExistingPivot($product->id, ['quantite' => $request->quantite]);

        return response()->json(['message' => __('Product quantity updated successfully.')], 200);
    }


    public function addProduct(Stock $stock, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
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
        $existingProduct = $stock->produits()->where('nom', $request->nom)->first();
        if ($existingProduct) {
            return response()->json(['message' => __('A product with the same name already exists in this stock.')], 422);
        }

        $product = Produit::where('nom', $request->nom)->first();

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        // Add the product to the stock
        $stock->produits()->attach($product->id, ['quantite' => 1]);

        // Create an entry in the user_produits table
        $userProduit = new UserProduit;
        $userProduit->user_id = $request->user()->id;
        $userProduit->produit_id = $product->id;
        $userProduit->custom_name = $request->nom;
        $userProduit->custom_code = $request->code;
        $userProduit->custom_image = $request->image;
        $userProduit->custom_description = $request->description;
        $userProduit->save();

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

        // Remove the product from the stock
        $stock->produits()->detach($product->id);

        // Find the UserProduit entry for the given user and product
        $userProduit = UserProduit::where('user_id', $request->user()->id)
            ->where('produit_id', $product->id)
            ->first();

        // Check if the UserProduit entry exists
        if ($userProduit) {
            // Delete the UserProduit entry
            $userProduit->delete();
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

        $products = $stock->produits->map(function ($product) use ($stock, $request) {
            // Fetch the user-specific information for the product
            $userProduit = UserProduit::where('user_id', $request->user()->id)
                ->where('produit_id', $product->id)
                ->first();

            // If user-specific information exists, use it to override the product details
            if ($userProduit) {
                $product->nom = $userProduit->custom_name ?? $product->nom;
                $product->description = $userProduit->custom_description ?? $product->description;
                $product->code = $userProduit->custom_code ?? $product->code;
                $product->image = $userProduit->custom_image ?? $product->image;
            }

            return [
                'id' => $product->id,
                'code' => $product->code,
                'nom' => $product->nom,
                'description' => $product->description,
                'prix' => $product->prix,
                'image' => $product->image,
                'categorie_id' => $product->categorie_id,
                'pivot' => [
                    'stock_id' => $stock->id,
                    'produit_id' => $product->id,
                    'quantite' => $product->pivot->quantite,
                ],
            ];
        });

        return response()->json($products);
    }
}
