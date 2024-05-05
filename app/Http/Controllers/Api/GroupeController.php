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
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $groupe = new Groupe;
        $groupe->fill($request->all());
        $groupe->proprietaire_id = $request->user()->id;
        $groupe->save();

        // Associate the user with the group
        $user = User::find($request->user()->id);

        $user->groupes()->attach($groupe);
        $groupe->load('stocks.produits', 'proprietaire');
        return response()->json($groupe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Groupe $groupe)
    {
        return response()->json($groupe);
    }

    public function groupStock($groupeId, Stock $stock)
    {
        $groupe = Groupe::find($groupeId);

        if (!$groupe) {
            return response()->json(['message' => __('Group not found.')], 404);
        }

        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Load the produits relationship on the stock
        $stock->load('produits');

        return response()->json($stock);
    }

    /*public function groupStockProduct($groupeId, Stock $stock, Produit $produit)
    {
        $groupe = Groupe::find($groupeId);

        if (!$groupe) {
            return response()->json(['message' => __('Group not found.')], 404);
        }

        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Load the produits relationship on the stock
        $stock->load('produits');

        // Check if the product is associated with the stock
        if (!$stock->produits->contains($produit->id)) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Load the stock relationship on the product
        $produit->load('stock');

        return response()->json($produit);
    }*/

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
            'nom' => 'sometimes|unique:groupes|max:255',
            'image' => 'sometimes|nullable|image',
            'proprietaire_id' => 'sometimes|exists:users,id',
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
    public function destroy($groupeId)
    {
        $groupe = Groupe::find($groupeId);

        // Dissociate the stocks from the group
        foreach ($groupe->stocks as $stock) {
            $groupe->stocks()->detach($stock->id);
        }

        foreach ($groupe->members as $member) {
            $groupe->members()->detach($member->id);
        }

        // Delete the group
        $groupe->delete();

        return response()->json(null, 204);
    }

    public function removeStockFromGroup($groupeId, Stock $stock)
    {
        $groupe = Groupe::find($groupeId);

        if (!$groupe) {
            return response()->json(['message' => __('Group not found.')], 404);
        }

        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Dissociate the stock from the group
        $stock->groupe_id = null;
        $stock->save();

        // Delete the stock
        $stock->delete();

        return response()->json(['message' => __('Stock removed successfully')], 204);
    }


    public function users(Groupe $groupe)
    {

        return response()->json($groupe->members()->get());
    }

    public function user(Groupe $groupe, User $user)
    {
        $user = $groupe->members()->find($user->id);
        if (!$user) {
            return response()->json(['message' => __('User is not a member of this group.')], 404);
        }
        return response()->json($user);
    }


    public function groupStocks(Groupe $groupe)
    {
        return response()->json($groupe->stocks);
    }

    public function addStockToGroup(Request $request, Groupe $groupe)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $stock = new Stock;
        $stock->nom = $request->nom;
        $stock->proprietaire_id = $request->user()->id;
        $stock->groupe()->associate($groupe);
        $stock->save();

        return response()->json(['message' => __('Stock added to the group successfully.')], 200);
    }

    public function associateUser(Request $request, $groupeid)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $groupe = Groupe::find($groupeid);

        $user = User::where('email', $request->email)->first();

        // Check if the user is already associated with the group
        if ($user->groupes()->where('groupe_id', $groupe->id)->exists()) {
            return response()->json(['message' => __('User is already a member of this group.')], 409);
        }

        // Associate the user with the group
        $user->groupes()->attach($groupe->id);

        return response()->json(['message' => __('User has been added to the group successfully!')], 200);
    }

    public function dissociateUser(Request $request, $groupeId, User $user)
    {
        // Manually retrieve the Groupe model instance
        $groupe = Groupe::findOrFail($groupeId);

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => __('User not found.')], 404);
        }

        // Check if the user is the owner of the group
        if ($groupe->proprietaire_id == $user->id) {
            return response()->json(['message' => __('You are the owner of this group and cannot be dissociated.')], 403);
        }

        // Check if the user is associated with the group
        if (!$groupe->members->contains('id', $request->user()->id)){
            return response()->json(['message' => __('You are not a member of this group.')], 403);
        }

        // Dissociate the user from the group
        $user->groupes()->detach($groupe);

        return response()->json(['message' => __('User has been removed from the group.')], 200);
    }

    public function groupStockProducts(Groupe $groupe, Stock $stock)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Get the products associated with the stock
        $products = $stock->produits;

        return response()->json($products);
    }

    public function addProduct(Groupe $groupe, Stock $stock, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'nullable|max:255',
            'nom' => 'required', // Ensure that 'nom' is always provided
            // Add other validation rules as needed
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $stock = $groupe->stocks->find($stock->id);

        // Check if the stock exists
        if (!$stock) {
            return response()->json(['message' => __('Stock not found.')], 404);
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
            return response()->json(['message' => __('Product quantity incremented.')], 200);
        } else {
            // The product is not in the stock, add it
            $stock->produits()->attach($product->id, ['quantite' => 1]);
            return response()->json(['message' => __('Product added to the stock successfully.')], 200);
        }
    }

    public function removeProductFromGroupStock(Groupe $groupe, Stock $stock, Produit $product)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Check if the product is associated with the stock
        if (!$stock->produits()->where('produit_id', $product->id)->exists()) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Dissociate the product from the stock
        $stock->produits()->detach($product);

        return response()->json(['message' => __('Product removed from the stock successfully.')], 200);
    }

    public function decreaseProductQuantityInGroupStock(Groupe $groupe, Stock $stock, Produit $product)
    {
        // Check if the stock is associated with the group
        if ($stock->groupe_id != $groupe->id) {
            return response()->json(['message' => __('This stock does not belong to this group.')], 404);
        }

        // Check if the product is associated with the stock
        $pivot = $stock->produits()->where('produit_id', $product->id)->first();
        if (!$pivot) {
            return response()->json(['message' => __('This product does not exist in this stock.')], 404);
        }

        // Check if the quantity is greater than 1
        if ($pivot->pivot->quantite > 1) {
            // Decrease the quantity of the product in the stock
            $stock->produits()->updateExistingPivot($product->id, ['quantite' => DB::raw('quantite - 1')]);
            return response()->json(['message' => __('Product quantity decreased successfully.')], 200);
        } else {
            // If the quantity is 1, remove the product from the stock
            $stock->produits()->detach($product);
            return response()->json(['message' => __('Product removed from the stock')], 200);
        }
    }
}
