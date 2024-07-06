<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutProduct extends Model
{
    use HasFactory;

    protected $table = 'checkout_product';
    protected $fillable = [
        'checkout_id', 'product_id'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id');
    }
}
