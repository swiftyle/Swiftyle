<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderStatusChangedNotification implements ShouldQueue
{
    public function handle(OrderStatusChanged $event)
    {
        $order = $event->order;
        $user = $order->user; // Assuming Order model has a user() relationship
        
        $user->notify(new OrderStatusChangedNotification($order));
    }
}
