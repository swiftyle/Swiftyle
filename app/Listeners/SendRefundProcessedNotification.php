<?php

namespace App\Listeners;

use App\Events\RefundProcessed;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRefundProcessedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  RefundProcessed  $event
     * @return void
     */
    public function handle(RefundProcessed $event)
    {
        $refundRequest = $event->refundRequest;

        // Ambil user yang terkait dengan produk yang meminta refund
        $user = $refundRequest->user;

        if ($user) {
            // Simpan notifikasi ke dalam database
            $user->notifications()->create([
                'type' => 'refund_processed',
                'data' => [
                    'refund_request_id' => $refundRequest->id,
                    'product_name' => $refundRequest->product->name,
                    'amount' => $refundRequest->amount,
                    'status' => $refundRequest->status,
                ]
            ]);
        }
    }
}
