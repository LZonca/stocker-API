<?php

namespace Database\Factories;

use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProduitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Produit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->numerify('PROD###'),
            'nom' => $this->faker->word,
            'description' => $this->faker->sentence,
            'prix' => $this->faker->randomFloat(2, 1, 100),
            'quantite' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(),
        ];
    }
}
