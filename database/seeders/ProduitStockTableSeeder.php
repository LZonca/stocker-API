<?php

namespace Database\Seeders;

use App\Models\Produit;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class ProduitStockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all products and stocks
        $produits = Produit::all();
        $stocks = Stock::all();

        // For each product, attach random stocks
        foreach ($produits as $produit) {
            $attachedStocks = $stocks->random(rand(1, 3))->pluck('id')->toArray();

            foreach ($attachedStocks as $stockId) {
                // Attach the stock to the product and set a random quantity
                $produit->stocks()->attach($stockId, ['quantite' => rand(1, 10)]);
            }
        }
    }
}
