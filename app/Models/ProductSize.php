<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSize extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'product_size';
    protected $primaryKey = 'id';
    protected $fillable = ['id','product_id', 'size_id'];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
    public function sizes()
    {
        return $this->hasMany(Size::class,'size_id');
    }


}
