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
        'confirmed_by'
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function refundRequest()
    {
        return $this->belongsTo(RefundRequest::class, 'refund_request_id', 'id');
    }
}
