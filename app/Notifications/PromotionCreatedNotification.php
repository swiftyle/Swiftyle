<?php

namespace App\Notifications;

use App\Models\Promotion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PromotionCreatedNotification extends Notification
{
    use Queueable;

    public $promotion;

    /**
     * Create a new notification instance.
     *
     * @param Promotion $promotion
     */
    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'promotion_id' => $this->promotion->id,
            'message' => 'Promotion baru telah dibuat di toko yang Anda ikuti: ' . $this->promotion->shop->name,
            'link' => '/promotions/' . $this->promotion->id, // Adjust the link as per your application
        ];
    }
}
