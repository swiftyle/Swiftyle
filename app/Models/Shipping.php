<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table ='shipping';
    protected $fillable = [
        'checkout_id',
        'shipping_address',
        'courier_name',
        'tracking_number',
        'shipped_date',
        'shipping_method',
        'shipping_cost',
        'shipping_status',
        'payment_status',
        'estimated_delivery_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'shipped_date',
        'estimated_delivery_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the checkout associated with the shipping.
     */
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Get the courier associated with the shipping.
     */


    
}
