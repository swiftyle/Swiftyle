<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductGenreFactory extends Factory
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
            'genre_id' => Genre::factory(), // Membuat atau mengambil ID dari instance Genre
        ];
    }
}
