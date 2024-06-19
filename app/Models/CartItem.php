<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cart_item';
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price', 'subtotal', 'coupon_id'];

    public function shop_coupon()
    {
        return $this->belongsTo(ShopCoupon::class);
    }
    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
