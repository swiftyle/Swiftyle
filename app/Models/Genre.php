<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres';
    protected $primaryKey = 'id';
    protected $fillable = ['name','description'];
    protected $hidden = ['created_at', 'updated_at'];

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_genre');
    }
}
