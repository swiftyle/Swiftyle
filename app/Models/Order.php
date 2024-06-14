<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'transaction_id',
        'shipping_id',
        'total',
        'status',
    ];
    
    public function shipping()
    {
        return $this->belongsTo(Shipping::class,'shipping_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id', 'id');
    }

    public function orderHistory()
    {
        return $this->hasOne(OrderHistory::class, 'order_id', 'id');
    }

    public function complaint()
    {
        return $this->hasOne(Complaint::class, 'order_id', 'id');
    }

    public function refundRequest()
    {
        return $this->hasOne(RefundRequest::class, 'order_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
    
}
