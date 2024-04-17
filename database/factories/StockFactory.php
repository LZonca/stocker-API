<?php

namespace Database\Factories;

use App\Models\Stock;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->word,
            'proprietaire_id' => \App\Models\User::factory(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Stock $stock) {
            // Create a product
            $product = Produit::factory()->create();

            // Attach the product to the stock
            $stock->produits()->attach($product->id, ['quantite' => 1]);
        });
    }
}
