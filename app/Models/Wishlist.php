<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlist';
    protected $fillable = [
        'id',
        'user_id',
    ];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'wishlist_item');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
