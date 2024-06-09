<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPromotion extends Model
{
    use HasFactory;

    protected $table = 'product_promotion';
    protected $fillable = ['product_id', 'promotion_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
    public function promotion()
    {
        return $this->hasMany(Promotion::class, 'promotion_id');
    }
}
