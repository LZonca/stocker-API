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
            'stock_id' => 1,
            'code' => $this->faker->numerify('PROD###'),
            'nom' => $this->faker->unique()->word, // Ensure 'nom' is unique
            'description' => $this->faker->sentence,
            'prix' => $this->faker->randomFloat(2, 1, 100),
            'image' => null,
            'expiry_date' => $this->faker->dateTime(),
            'quantite' => $this->faker->randomDigitNotNull()
        ];
    }
}
