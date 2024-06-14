<?php

namespace App\Listeners;

use App\Events\ReviewCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReviewCreatedNotification;

class SendReviewCreatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ReviewCreated  $event
     * @return void
     */
    public function handle(ReviewCreated $event)
    {
        // Retrieve the review from the event
        $review = $event->review;

        // Check if the review belongs to a product, and get the seller user
        if ($review->product && $review->product->shop) {
            $seller = $review->product->shop->user;

            // Send notification to the seller
            if ($seller) {
                $seller->notify(new ReviewCreatedNotification($review));
            }
        }
    }
}
