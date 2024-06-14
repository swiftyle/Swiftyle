<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'promotions';
    protected $primary = 'id';

    protected $fillable = [
        'id',
        'name',
        'description',
        'type',
        'discount_amount',
        'discount_percentage',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'percentage_discount',
        'discount_amount' => null,
        'discount_percentage' => null,
    ];

    /**
     * Get the products associated with the promotion.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product', 'promotion_id', 'product_id')
            ->withPivot('discount_amount', 'discount_percentage')
            ->withTimestamps();
    }

}
