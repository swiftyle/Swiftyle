<?php

namespace App\Events;

use App\Models\RefundRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $refundRequest;

    /**
     * Create a new event instance.
     *
     * @param RefundRequest $refundRequest
     */
    public function __construct(RefundRequest $refundRequest)
    {
        $this->refundRequest = $refundRequest;
    }
}
