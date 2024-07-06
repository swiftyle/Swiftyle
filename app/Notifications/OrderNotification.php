<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $userId;
    protected $timestamp;

    /**
     * Create a new notification instance.
     *
     * @param int $userId
     * @param string $timestamp
     * @return void
     */
    public function __construct($userId, $timestamp)
    {
        $this->userId = $userId;
        $this->timestamp = $timestamp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Order Checkout Successful')
                    ->line("Your order has been successfully checked out.")
                    ->line("User ID: {$this->userId}")
                    ->line("Time: {$this->timestamp}")
                    ->action('View Order', url('/orders'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Order has been successfully checked out by user ID: {$this->userId} at {$this->timestamp}",
            'user_id' => $this->userId,
            'timestamp' => $this->timestamp,
        ];
    }
}
