<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\User;
use App\Models\UserProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $messages = [
            'password.min' => __('The password must be at least 12 characters.'),
            'password.lowercase' => __('The password must contain at least one lowercase letter.'),
            'password.uppercase' => __('The password must contain at least one uppercase letter.'),
            'password.digit' => __('The password must contain at least one digit.'),
            'password.special' => __('The password must contain at least one special character.'),
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email', // Removed unique:users
            'password' => [
                'sometimes',
                'required',
                'min:12',
                'lowercase' => 'regex:/[a-z]/',      // must contain at least one lowercase letter
                'uppercase' => 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'digit' => 'regex:/[0-9]/',          // must contain at least one digit
                'special' => 'regex:/[@$!%*#?&]/',
            ],
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create a token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch the error if the email is a duplicate
            if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for a unique constraint violation
                return response()->json(['message' => __('The email has already been taken.')], 409);
            }

            // If the error is not due to a duplicate email, rethrow the exception
            throw $e;
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|email',
            'password' => [
                'sometimes',
                'required',
                'min:12',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                function ($attribute, $value, $fail) use ($request) {
                    if (Hash::check($value, $request->user()->password)) {
                        $fail(__('The new password cannot be the same as the old password.'));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $updateData = [];

        if ($request->has('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->has('email')) {
            $updateData['email'] = $request->email;
        }

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        try {
            $request->user()->update($updateData);
            return response()->json($request->user());
        } catch (\Illuminate\Database\QueryException $e) {
            // Catch the error if the email is a duplicate
            if ($e->getCode() == 23000) { // 23000 is the SQLSTATE code for a unique constraint violation
                return response()->json(['message' => __('The email has already been taken.')], 409);
            }

            // If the error is not due to a duplicate email, rethrow the exception
            throw $e;
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }

    public function groups(Request $request)
    {
        $groups = $request->user()->groupes;

        // Eager load the proprietaire, members and userProduits relationships
        $groups->load([
            'proprietaire',
            'members',
            'stocks.produits' => function ($query) use ($request) {
                $query->with(['userProduits' => function ($query) use ($request) {
                    $query->where('user_id', $request->user()->id);
                }]);
            },
        ]);

        // Iterate over each group
        foreach ($groups as $group) {
            // Iterate over each stock in the group
            foreach ($group->stocks as $stock) {
                // Iterate over each produit in the stock
                foreach ($stock->produits as $produit) {
                    // If user-specific information exists, use it to override the product details
                    if ($produit->userProduits) {
                        $produit->nom = $produit->userProduits->custom_name ?? $produit->nom;
                        $produit->description = $produit->userProduits->custom_description ?? $produit->description;
                        $produit->image = $produit->userProduits->custom_image ?? $produit->image;
                    }
                }
            }
        }

        return response()->json($groups);
    }

    public function login(Request $request)
    {
        $user= User::where('email', $request->email)->first();

        if (!$user) {
            return response([
                'message' => [__('No user found with email: :email', ['email' => $request->email])]
            ], 404);
        } else if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => [__('Password does not match for user: :email', ['email' => $request->email])]
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response([
                'message' => [__('These credentials do not match our records.')]
            ], 404);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function leaveGroup(Request $request, $groupId)
    {
        // Retrieve the group by its ID
        $group = Groupe::find($groupId);

        // Check if the group exists
        if (!$group) {
            return response()->json(['message' => __('Group not found.')], 404);
        }

        // Check if the user is a member of the group
        if (!$request->user()->groupes()->where('groupes.id', $group->id)->exists()) {
            return response()->json(['message' => __('You are not a member of this group.')], 403);
        }

        // Detach the user from the group
        $request->user()->groupes()->detach($group->id);

        return response()->json(['message' => __('Successfully left the group.')], 200);
    }

}
