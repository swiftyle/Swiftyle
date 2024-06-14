<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Style;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductStyleFactory extends Factory
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
            'style_id' => Style::factory(), // Membuat atau mengambil ID dari instance Style
        ];
    }
}
