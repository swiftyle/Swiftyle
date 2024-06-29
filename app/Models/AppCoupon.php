<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppCoupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
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
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
