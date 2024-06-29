<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'order_id',
        'status',
        'description',
    ];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}
