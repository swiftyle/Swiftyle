<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'shop_id' => Shop::inRandomOrder()->first()->id, // Assuming you have a ShopFactory to generate shops
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'image' => 'images/' . $this->faker->image('storage/app/public/images', 640, 480, null, false),
            'sell' => $this->faker->numberBetween(0, 1000),
            'rating' => $this->faker->randomFloat(2, 1, 5),
        ];
    }
}
