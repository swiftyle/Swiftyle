<?php

namespace App\Listeners;

use App\Events\RefundProcessed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Color;
use Illuminate\Support\Facades\Log;

class IncreaseStock
{
    /**
     * Handle the event.
     *
     * @param  RefundProcessed  $event
     * @return void
     */
    public function handle(RefundProcessed $event)
    {
        $refund = $event->refund;

        // Loop through each item in the refund
        foreach ($refund->refundRequest->order->shipping->checkout->cart->cartItems as $cartItem) {
            $product = $cartItem->product;

            foreach ($product->sizes as $size) {
                $sizeColor = $size->colors()->wherePivot('size_id', $size->id)->first();

                if ($sizeColor) {
                    $sizeColor->pivot->stock += $cartItem->quantity;
                    $sizeColor->pivot->save();

                    // Log for debugging purposes
                    Log::info("Stock increased for Color ID: {$sizeColor->id}, Size ID: {$size->id}, Quantity: {$cartItem->quantity}");
                }
            }
        }
    }
}
