<?php

namespace Tests\Feature;

use App\Models\Produit;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserStockTest extends TestCase
{
    use RefreshDatabase;

    public function testAddProduitToUserStockWithValidArgumentsShouldSucceed()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456789'),
        ]);
        Produit::factory()->count(3)->create();

        $stock = Stock::factory()->create(['proprietaire_id' => $user->id]);

        $this->assertDatabaseHas('stocks', [
            'id' => $stock->id,
            'proprietaire_id' => $user->id,
        ]);

        $product = Produit::factory()->create([
            'code' => '123456789',
            'nom' => 'test',
            'description' => 'test',
            'prix' => 10,
        ]);

        $this->assertDatabaseHas('produits', [
            'id' => $product->id,
            'code' => '123456789',
        ]);

        $response = $this->actingAs($user)->post("/user/stocks/{$stock->id}/produits", [
            'code' => $product->code,
            'nom' => $product->nom,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('produit_stock', [
            'stock_id' => $stock->id,
            'produit_id' => $product->id,
        ]);
    }

    public function testAddProduitToUserStockWithInvalidArgumentsShouldFail()
    {
        $user = User::factory()->create();
        Produit::factory()->count(3)->create();

        $stock = Stock::factory()->create(['proprietaire_id' => $user->id]);

        $response = $this->actingAs($user)->post("/user/stocks/{$stock->id}/produits", [
            'code' => '', // Invalid product code
            'nom' => '', // Invalid product name
        ]);

        $response->assertStatus(422);
    }

    /*public function testAddExisingProduitToUserStockShouldIncrement()
    {
        $user = User::factory()->create();
        $produit = ProductView::factory()->create([
            'code' => '123456789',
            'nom' => 'test',
            'description' => 'test',
            'prix' => 10,
        ]);


        ProductView::factory()->count(3)->create();
        $stock = Stock::factory()->create(['proprietaire_id' => $user->id]);


        // Add the product to the stock
        $response = $this->actingAs($user)->post("/users/$user->id/stocks/$stock->id/produits", [
            'code' => $produit->code,
            'nom' => $produit->nom,
        ]);


        // Check that the product has been added to the stock with a quantity of 1
        $this->assertDatabaseHas('produit_stock', [
            'stock_id' => $stock->id,
            'produit_id' => $produit->id,
            'quantite' => 2,
        ]);

        // Check that the quantity of the specific product in the stock is now 2
    }*/
}
