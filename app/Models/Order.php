<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'shipping_id',
        'total',
        'status',
    ];

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviews()
    {
        return $this->hasOne(Review::class, 'order_id', 'id');
    }

    public function orderHistory()
    {
        return $this->hasOne(OrderHistory::class, 'order_id', 'id');
    }

    public function complaints()
    {
        return $this->hasOne(Complaint::class, 'order_id', 'id');
    }

    public function refundRequest()
    {
        return $this->hasOne(RefundRequest::class, 'order_id', 'id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id');
    }
}
