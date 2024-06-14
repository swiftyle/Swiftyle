<?php

// App/Models/Cart.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'app_coupon_id', 'quantity', 'price', 'discount', 'total_discount', 'subtotal', 'total_price',
    ];

    /**
     * Boot method for the model.
     */

    /**
     * Get the product that owns the cart item.
     */
    public function cartItem()
    {
        return $this->belongsToMany(Product::class, 'cart_item');
    }

    public function checkout()
    {
        return $this->hasOne(Checkout::class);
    }
    public function appCoupon()
    {
        return $this->hasOne(AppCoupon::class);
    }

}
