<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Events\OrderReceived;
use App\Events\OrderReviewed;
use App\Events\OrderPackaged;
use App\Events\OrderShipped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateOrderStatus
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        $order = $event->order;

        if ($event instanceof OrderPackaged) {
            $order->status = 'packaged';
        } elseif ($event instanceof OrderShipped) {
            $order->status = 'shipped';
        } elseif ($event instanceof OrderDelivered) {
            $order->status = 'delivered';
        } elseif ($event instanceof OrderReceived) {
            $order->status = 'received';
        } elseif ($event instanceof OrderReviewed) {
            $order->status = 'reviewed';
        }

        $order->save();
    }
}
