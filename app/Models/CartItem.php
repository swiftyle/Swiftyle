<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_item';
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price', 'subtotal', 'coupon_id'];

    public function shop_coupon()
    {
        return $this->belongsTo(ShopCoupon::class);
    }
}
