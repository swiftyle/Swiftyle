<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;
    protected $table ='sizes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
    ];

    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class,'product_size');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'size_color')
                    ->withPivot('stock')
                    ->withTimestamps();
    }
}
