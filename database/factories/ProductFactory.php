<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'image' => $this->faker->imageUrl(640, 480, 'products', true),
            'sell' => $this->faker->numberBetween(0, 100),
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'deleted_at' => null,
        ];
    }
}
