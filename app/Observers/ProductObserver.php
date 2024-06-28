<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */

     public function created(Product $product)
    {
        // Ambil subkategori yang terkait dengan produk
        $subCategory = $product->subcategories()->first();

        // Pastikan subCategory tidak null
        if ($subCategory) {
            // Ambil main_category_id dari subkategori
            $mainCategoryId = $subCategory->main_category_id;

            // Inisialisasi prefix dan counter
            $mainCategoryPrefix = str_pad($mainCategoryId, 3, '0', STR_PAD_LEFT); // Mengasumsikan main_category_id tiga digit
            $subcategoryPrefix = str_pad($subCategory->id, 3, '0', STR_PAD_LEFT); // Mengasumsikan id subkategori tiga digit
            $productId = str_pad($product->id, 6, '0', STR_PAD_LEFT); // Mengasumsikan id produk enam digit

            // Menghasilkan custom ID
            $customId = substr($mainCategoryPrefix . $subcategoryPrefix . $productId, 0, 12);

            // Perbarui produk dengan custom ID
            $product->custom_id = $customId;
            $product->save();
        } else {
            Log::error('Subcategory not found for product ID: ' . $product->id);
        }
    }



    /**
     * Handle the Product "updated" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
