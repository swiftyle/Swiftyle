<?php

// App/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description', 'price', 'image', 'shop_id', 'rating', 'sell'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer',
    ];
    protected $dates = ['created_at', 'updated_at','deleted_at'];

    /**
     * Boot method for the model.
     */

    /**
     * Get the category that owns the product.
     */
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

    public function cart()
    {
        return $this->belongsToMany(Cart::class, 'cart_item');
    }
    public function wishlist()
    {
        return $this->belongsToMany(Wishlist::class, 'wishlist_item');
    }
    public function review()
    {
        return $this->hasMany(Review::class);
    }
    public function mainCategory()
    {
        return $this->hasOneThrough(MainCategory::class, SubCategory::class);
    }
}
