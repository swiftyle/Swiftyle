<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(), // Membuat atau mengambil ID dari instance Product
            'promotion_id' => Promotion::factory(), // Membuat atau mengambil ID dari instance Promotion
        ];
    }
}
