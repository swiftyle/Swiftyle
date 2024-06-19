<?php

namespace App\Listeners;

use App\Events\ComplaintSubmitted;
use App\Notifications\ComplaintCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendComplaintCreatedNotification implements ShouldQueue
{
    public function handle(ComplaintSubmitted $event)
    {
        $complaint = $event->complaint;
        $seller = $complaint->order->shipping->checkout->cart->cartItem->product->shop->user;

        if ($seller) {
            $seller->notify(new ComplaintCreatedNotification($complaint));
        }
    }
}
