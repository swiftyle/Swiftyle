<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{

    protected $fillable = [
        'id','order_id', 'user_id', 'payment_method',
    ];
    protected $primaryKey = 'id';
    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = UuidHelper::generateUuid();
            }
        });
    }
}
