<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Categorie::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:categories|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categorie = new Categorie;
        $categorie->fill($request->all());
        $categorie->save();

        return response()->json($categorie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        return response()->json($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:categories,nom,' . $categorie->id . '|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $categorie->fill($request->all());
        $categorie->save();

        return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json(null, 204);
    }

    /**
     * Link a product to a category.
     */
    public function linkToCategorie(Request $request, $produitId, $categorieId)
    {
        $produit = Produit::find($produitId);
        $categorie = Categorie::find($categorieId);

        if (!$produit || !$categorie) {
            return response()->json(['message' => 'ProductView or Categorie not found'], 404);
        }

        $produit->categorie()->associate($categorie);
        $produit->save();

        return response()->json($produit);
    }

    /**
     * Unlink a product from a specific category.
     */
    public function unlinkFromCategorie($produitId, $categorieId)
    {
        $produit = Produit::find($produitId);
        $categorie = Categorie::find($categorieId);

        if (!$produit || !$categorie) {
            return response()->json(['message' => 'ProductView or Categorie not found'], 404);
        }

        if ($produit->categorie->id != $categorie->id) {
            return response()->json(['message' => 'ProductView is not linked to the specified Categorie'], 400);
        }

        $produit->categorie()->dissociate();
        $produit->save();

        return response()->json($produit);
    }
}
