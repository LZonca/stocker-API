<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produit;
use App\Models\Stock;

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
            $produit->stocks()->attach(
                $stocks->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
