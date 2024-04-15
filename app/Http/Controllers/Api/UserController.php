<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json($user->load('stocks.produits', 'groupes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'required|min:8',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
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

/*    /**
     * Display the stocks of every user.
     */
    /*public function stocks(User $user)
    {
        return response()->json($user->stocks);
    }*/


}
