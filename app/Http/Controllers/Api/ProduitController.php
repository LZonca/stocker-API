<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return(Produit::all()->toJson());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{

    $validator = Validator::make($request->all(), [
        'code' => 'nullable|unique:produits|max:255',
        'nom' => 'required|max:255',
        'description' => 'nullable|string',
        'prix' => 'nullable|numeric|min:0',
        'expiry_date' => 'nullable|date',
        'image' => 'nullable|image',
        'categorie_id' => 'nullable|exists:categories,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $produit = new Produit;
    $produit->fill($request->all());
    $produit->save();

    return response()->json($produit, 201);
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
    public function update(Request $request, Produit $produit)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'nullable|unique:produits|max:255',
            'nom' => 'required|max:255',
            'description' => 'nullable|string',
            'prix' => 'nullable|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'image' => 'nullable|image',
            'categorie_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $produit->update($request->only(['nom', 'code', 'description', 'image', 'expiry_date', 'prix']));

        return response()->json($produit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produit $produit)
    {
        $produit->delete();

        return response()->json(null, 204);
    }
}
