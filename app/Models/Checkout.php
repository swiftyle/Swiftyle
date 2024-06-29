<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'checkouts';
    protected $fillable = [
        'cart_id',
        'payment_id',
        'address_id',
        'courier_id',
        'total_amount',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class);
    }
}
