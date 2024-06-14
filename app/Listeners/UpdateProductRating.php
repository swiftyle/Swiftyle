<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateProductRating
{
    /**
     * Handle the event.
     *
     * @param  ReviewCreated  $event
     * @return void
     */
    public function handle(ReviewCreated $event)
    {
        $review = $event->review;
        $product = $review->product;

        // Hitung ulang rating rata-rata untuk produk ini
        $averageRating = $product->reviews()->avg('rating');

        // Perbarui rating produk
        $product->rating = $averageRating;
        $product->save();
    }
}
