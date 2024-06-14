<?php

namespace App\Listeners;

use App\Events\PromotionCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PromotionCreatedNotification;

class SendPromotionCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  PromotionCreated  $event
     * @return void
     */
    public function handle(PromotionCreated $event)
    {
        // Retrieve the promotion from the event
        $promotion = $event->promotion;

        // Get the shop associated with the promotion
        $shop = $promotion->shop;

        // Retrieve all users who follow this shop
        $followers = $shop->followers;

        // Send notification to each follower
        foreach ($followers as $follower) {
            $follower->notify(new PromotionCreatedNotification($promotion));
        }
    }
}
