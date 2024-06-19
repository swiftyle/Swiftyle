<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;
    protected $table = 'wishlist_item';
    protected $fillable = [
        'wishlist_id',
        'product_id',
    ];
    
    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
