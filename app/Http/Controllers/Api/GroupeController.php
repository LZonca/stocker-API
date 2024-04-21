<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GroupeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Groupe::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is typically used in web applications to show a form for creating a new resource.
        // Since this is an API, you might not need this method.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:groupes|max:255',
            'image' => 'nullable|image',
            'proprietaire_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $groupe = new Groupe;
        $groupe->fill($request->all());
        $groupe->save();

        // Associate the user with the group
        $user = User::find($request->proprietaire_id);
        $user->groupes()->attach($groupe);

        return response()->json($groupe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Groupe $groupe)
    {
        return response()->json($groupe);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Groupe $groupe)
    {
        // This method is typically used in web applications to show a form for editing an existing resource.
        // Since this is an API, you might not need this method.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Groupe $groupe)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:groupes|max:255',
            'image' => 'nullable|image',
            'proprietaire_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $groupe->update($request->all());
        return response()->json($groupe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Groupe $groupe)
    {
        $groupe->delete();
        return response()->json(null, 204);
    }

    public function users(Groupe $groupe)
    {
        return response()->json($groupe->users);
    }

    public function user(Groupe $groupe, User $user)
    {
        $user = $groupe->users->find($user->id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        return response()->json($user);
    }


    public function groupStocks(Groupe $groupe)
    {
        return response()->json($groupe->stocks);
    }

    public function addStockToGroup(Groupe $groupe, Stock $stock)
    {
        $stock->groupe()->associate($groupe);
        $stock->save();

        return response()->json(['message' => 'Stock associated to the group successfully']);
    }

    public function associateUser(User $user, Groupe $group)
    {
        // Check if the user is already associated with the group
        if ($user->groupes()->where('groupe_id', $group->id)->exists()) {
            return response()->json(['message' => 'User is already associated with this group.'], 409);
        }

        // Associate the user with the group
        $user->groupes()->attach($group);

        return response()->json(['message' => 'User associated with the group successfully.'], 200);
    }

    public function dissociateUser(User $user, Groupe $group)
    {
        // Check if the user is associated with the group
        if (!$user->groupes()->where('groupe_id', $group->id)->exists()) {
            return response()->json(['message' => 'User is not associated with this group.'], 404);
        }

        // Dissociate the user from the group
        $user->groupes()->detach($group);

        return response()->json(['message' => 'User dissociated from the group successfully.'], 200);
    }

    public function groupStockProducts(Groupe $groupe, Stock $stock)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => 'Stock is not associated with this group.'], 404);
        }

        // Get the products associated with the stock
        $products = $stock->produits;

        return response()->json($products);
    }

    public function addProduct(Groupe $groupe, Stock $stock, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'nom' => 'required', // Ensure that 'nom' is always provided
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $groupe->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => 'Stock not found.'], 404);
        }

        $product = Produit::where('nom', $request->nom)->first();

        if (!$product) {
            // The product is not found, create it
            $product = Produit::create($request->all());
        }

        // Check if the product's code is already in the stock
        $pivot = $stock->produits()->where('code', $product->code)->first();

        if ($pivot) {
            // The product is already in the stock, increment the quantity
            $pivot = $stock->produits()->where('produit_id', $product->id)->first();
            $pivot->pivot->quantite += 1;
            $pivot->pivot->save();
            return response()->json(['message' => 'Product quantity incremented.'], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id, ['quantite' => 1]);
            return response()->json(['message' => 'Product added to stock successfully.'], 200);
        }

        /*if ($pivot) {
            // The product is already in the stock, increment the quantity
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite + 1')]);
            return response()->json(['message' => 'Product quantity incremented.'], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id, ['quantite' => 1]);
            return response()->json(['message' => 'Product added to stock successfully.'], 200);
        }*/
    }

    public function removeProductFromGroupStock(Groupe $groupe, Stock $stock, Produit $product)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => 'Stock is not associated with this group.'], 404);
        }

        // Check if the product is associated with the stock
        if (!$stock->produits()->where('produit_id', $product->id)->exists()) {
            return response()->json(['message' => 'Product is not associated with this stock.'], 404);
        }

        // Dissociate the product from the stock
        $stock->produits()->detach($product);

        return response()->json(['message' => 'Product removed from the stock successfully.'], 200);
    }

    public function decreaseProductQuantityInGroupStock(Groupe $groupe, Stock $stock, Produit $product)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => 'Stock is not associated with this group.'], 404);
        }

        // Check if the product is associated with the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();
        if (!$pivot) {
            return response()->json(['message' => 'Product is not associated with this stock.'], 404);
        }

        // Check if the quantity is greater than 1
        if ($pivot->pivot->quantite > 1) {
            // Decrease the quantity of the product in the stock
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite - 1')]);
            return response()->json(['message' => 'Product quantity decreased.'], 200);
        } else {
            // If the quantity is 1, remove the product from the stock
            $stock->produits()->detach($product);
            return response()->json(['message' => 'Product removed from the stock.'], 200);
        }
    }
}
