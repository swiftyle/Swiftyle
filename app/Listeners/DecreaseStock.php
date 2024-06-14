<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DecreaseStock
{
    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

        // Loop through each order item
        foreach ($order->shipping->checkout->cart->cartItems as $cartItem) {
            $product = $cartItem->product;


            // Decrease the stock in the pivot table size_color
            foreach ($product->sizes as $size) {
                $sizeColor = $size->colors()->wherePivot('size_id', $size->id)->first();

                if ($sizeColor) {
                    $sizeColor->pivot->stock -= $cartItem->quantity;
                    $sizeColor->pivot->save();
                    Log::info("Stock Decreased for Color ID: {$sizeColor->id}, Size ID: {$size->id}, Quantity: {$cartItem->quantity}");

                }
            }
        }
    }
}
