<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShoppingListController extends Controller
{
    // Create a new shopping list
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $shoppingList = new ShoppingList();
        $shoppingList->fill($request->all());
        $shoppingList->stock_id = $request->user()->id;
        $shoppingList->save();

        return response()->json($shoppingList, 201);
    }

    // Edit an existing shopping list
    public function edit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $shoppingList = ShoppingList::findOrFail($id);

        $shoppingList->name = $validated['name'];

        return response()->json($shoppingList);
    }

    // Delete a shopping list
    public function delete($id)
    {
        $shoppingList = ShoppingList::findOrFail($id);
        $this->authorize('delete', $shoppingList);

        $shoppingList->delete();

        return response()->json(null, 204);
    }

    // View a shopping list
    public function show(Request $request, ShoppingList $shoppingList)
    {
        $shoppingList->load('shoppinglist.produits', 'members', 'proprietaire');

        return response()->json($shoppingList);
    }

    // Add a product to a shopping list
    public function addProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $shoppingList = ShoppingList::findOrFail($id);
        $this->authorize('update', $shoppingList);

        $shoppingListUserProduit = ShoppingListUserProduit::create([
            'shopping_list_id' => $shoppingList->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        return response()->json($shoppingListUserProduit, 201);
    }

    // Remove a product from a shopping list
    public function removeProduct($id, $productId)
    {
        $shoppingList = ShoppingList::findOrFail($id);
        $this->authorize('update', $shoppingList);

        $shoppingListUserProduit = ShoppingListUserProduit::where('shopping_list_id', $id)
            ->where('product_id', $productId)
            ->firstOrFail();

        $shoppingListUserProduit->delete();

        return response()->json(null, 204);
    }
}
