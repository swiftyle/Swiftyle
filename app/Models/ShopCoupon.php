<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCoupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'shop_id',
        'name',
        'code',
        'type',
        'discount_amount',
        'max_uses',
        'used_count',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
       'shop_id' => 'integer',
        'type' => 'integer',
       'max_uses' => 'integer',
        'used_count' => 'integer',
        'discount_amount' => 'float',
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    /**
     * Get the shop that owns the coupon.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the coupons that belong to the shop.
     */
    public function cartItem()
    {
        return $this->belongsToMany(CartItem::class);
    }
}
