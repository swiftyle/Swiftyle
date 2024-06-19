<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotification implements ShouldQueue
{
    public function handle(OrderCreated $event)
    {
        $order = $event->order;
        $user = $order->user;

        $user->notify(new OrderCreatedNotification($order));
    }
}
