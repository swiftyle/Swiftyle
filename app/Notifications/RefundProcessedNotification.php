<?php

namespace App\Notifications;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RefundProcessedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $refundRequest;

    /**
     * Create a new notification instance.
     *
     * @param RefundRequest $refundRequest
     */
    public function __construct(RefundRequest $refundRequest)
    {
        $this->refundRequest = $refundRequest;
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
    public function toArray($notifiable)
    {
        return [
            'type' => 'refund_processed',
            'refund_request_id' => $this->refundRequest->id,
            'product_name' => $this->refundRequest->product->name,
            'amount' => $this->refundRequest->amount,
            'status' => $this->refundRequest->status,
        ];
    }
}
