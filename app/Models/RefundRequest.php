<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $table ='refund_requests';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'user_id', 'order_id', 'reason', 'status'
    ];
    protected $casts = [
        'id' =>'string',
        'user_id' =>'string',
        'order_id' =>'string',
       'reason' =>'string',
       'status' =>'string'
    ];
    protected $keyType ='string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}
