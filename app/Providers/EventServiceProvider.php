<?php

namespace App\Providers;

use App\Events\NewFollower;
use App\Listeners\DecreaseStock;
use App\Events\OrderCreated;
use App\Events\OrderDelivered;
use App\Events\OrderPackaged;
use App\Events\OrderReceived;
use App\Events\OrderReviewed;
use App\Events\OrderShipped;
use App\Events\ProductCreated;
use App\Events\PromotionCreated;
use App\Events\RefundProcessed;
use App\Events\RefundRequested;
use App\Events\ReviewCreated;
use App\Listeners\HandleOrderReviewed;
use App\Listeners\IncreaseStock;
use App\Listeners\SendNewFollowerNotification;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\SendProductCreatedNotification;
use App\Listeners\SendPromotionCreatedNotification;
use App\Listeners\SendRefundProcessedNotification;
use App\Listeners\SendRefundRequestedNotification;
use App\Listeners\SendReviewCreatedNotification;
use App\Listeners\UpdateOrderStatus;
use App\Listeners\UpdateProductRating;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreated::class => [
            DecreaseStock::class,
            SendOrderCreatedNotification::class,
        ],
        OrderDelivered::class => [
            UpdateOrderStatus::class,
            SendOrderCreatedNotification::class,
        ],
        OrderReceived::class => [
            UpdateOrderStatus::class,
            SendOrderCreatedNotification::class,
        ],
        OrderReviewed::class => [
            UpdateOrderStatus::class,
            HandleOrderReviewed::class,
            SendOrderCreatedNotification::class,
        ],
        OrderPackaged::class => [
            UpdateOrderStatus::class,
            SendOrderCreatedNotification::class,
        ],
        OrderShipped::class => [
            UpdateOrderStatus::class,
            SendOrderCreatedNotification::class,
        ],
        ProductCreated::class => [
            SendProductCreatedNotification::class,
        ],
        PromotionCreated::class => [
            SendPromotionCreatedNotification::class,
        ],
        RefundRequested::class => [
            SendRefundRequestedNotification::class,
        ],
        ReviewCreated::class => [
            UpdateProductRating::class,
            SendReviewCreatedNotification::class,
        ],
        RefundProcessed::class => [
            IncreaseStock::class,
            SendRefundProcessedNotification::class,
        ],
        NewFollower::class => [
            SendNewFollowerNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
