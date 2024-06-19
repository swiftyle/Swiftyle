<?php

// app/Models/Refund.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'refund_request_id',
        'amount',
        'status',
    ];

    public function refundRequest()
    {
        return $this->belongsTo(RefundRequest::class);
    }
}
