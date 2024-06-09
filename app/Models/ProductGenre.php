<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGenre extends Model
{
    use HasFactory;

    protected $table = 'product_genre';
    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'genre_id',
    ];

    protected $dates = ['created_at', 'updated_at','deleted_at'];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
    public function genre()
    {
        return $this->hasMany(Genre::class, 'genre_id');
    }
    
}
