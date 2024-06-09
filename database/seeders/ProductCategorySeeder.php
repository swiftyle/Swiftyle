<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create categories
        $categories = SubCategory::factory()->count(5)->create();

        // Initialize counters for each category
        $categoryCounters = [];

        // Create products and assign them to categories
        $products = Product::factory()->count(10)->create()->each(function ($product) use ($categories, &$categoryCounters) {
            $category = $categories->random();

            // Initialize counter for this category if not already initialized
            if (!isset($categoryCounters[$category->id])) {
                $categoryCounters[$category->id] = 1;
            }

            // Generate custom ID
            $categoryPrefix = str_pad($category->id, 3, '0', STR_PAD_LEFT);
            $productId = str_pad($categoryCounters[$category->id], 6, '0', STR_PAD_LEFT);
            $product->id = $categoryPrefix . $productId;
            $product->save();

            // Increment the counter for this category
            $categoryCounters[$category->id]++;

            // Attach product to category (this creates the product-category association)
            $product->subcategories()->attach($category->id, [
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]);
        });
    }
}
