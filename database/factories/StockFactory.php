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
            $produits = Produit::all();
            $stock->produits()->attach(
                $produits->random(rand(1, 3))->pluck('id')->toArray()

            );
        });
    }
}
