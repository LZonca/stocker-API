<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

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
        $produit->fill($request->all());
        $produit->save();

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
