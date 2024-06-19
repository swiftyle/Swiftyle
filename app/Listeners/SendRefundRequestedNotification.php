<?php

namespace App\Listeners;

use App\Events\RefundRequested;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRefundRequestedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  RefundRequested  $event
     * @return void
     */
    public function handle(RefundRequested $event)
    {
        $refundRequest = $event->refundRequest;

        // Ambil user yang terkait dengan produk yang meminta refund
        $user = $refundRequest->product->shop->user;

        if ($user) {
            // Simpan notifikasi ke dalam database
            $user->notifications()->create([
                'type' => 'refund_requested',
                'data' => [
                    'refund_request_id' => $refundRequest->id,
                    'product_name' => $refundRequest->product->name,
                    'amount' => $refundRequest->amount,
                    'reason' => $refundRequest->reason,
                ]
            ]);
        }
    }
}
