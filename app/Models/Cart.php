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
        'id', 'user_id', 'app_coupon_id', 'quantity', 'price', 'discount', 'total_discount', 'subtotal', 'total_price',
    ];
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'app_coupon_id' => 'integer',
        'quantity' => 'integer',];
    protected $dates = ['deleted_at'];

    protected $hidden = ['deleted_at','created_at', 'update_at'];

    /**
     * Boot method for the model.
     */

    /**
     * Get the product that owns the cart item.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
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
