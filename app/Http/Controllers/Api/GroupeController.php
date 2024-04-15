<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use Illuminate\Http\Request;

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
        $groupe = Groupe::create($request->all());
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
}
