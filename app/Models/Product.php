<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'name', 'description', 'price', 'image', 'shop_id', 'rating', 'sell'
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    protected $casts = [
        'price' => 'integer',
    ];
    protected $dates = ['created_at', 'updated_at','deleted_at'];

    public function subcategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_category');
    }

    public function styles()
    {
        return $this->belongsToMany(Style::class,'product_style');
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'product_promotion');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }

    public function shops()
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Wishlist::class, 'wishlist_item');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function mainCategory()
    {
        return $this->hasOneThrough(MainCategory::class, SubCategory::class);
    }

    public function sizeColors()
    {
        return $this->hasMany(SizeColor::class);
    }

    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'checkout_product');
    }
}
