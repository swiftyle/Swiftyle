<?php

namespace App\Listeners;

use App\Events\OrderReviewed;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleOrderReviewed implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  OrderReviewed  $event
     * @return void
     */
    public function handle(OrderReviewed $event)
    {
        $order = $event->order;

        // Call the method to save user preferences
        $this->saveUserPreferences($order);
    }

    private function saveUserPreferences(Order $order)
    {
        $user = $order->user;
        
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== $user->id) {
            return;
        }

        // Get the product from the order
        $product = $order->shipping->checkout->cart->cartItems()->first()->product;

        if (!$product) {
            return;
        }

        // Get the style_id associated with the product
        $styleId = $product->styles()->first()->id;

        // Save the preference (assuming user_preferences is a pivot table)
        $user->preferences()->syncWithoutDetaching([$styleId]);
    }
}
