<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    protected $table = 'product_size';
    protected $primaryKey = 'id';
    protected $fillable = ['id','product_id', 'size_id'];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
    public function size()
    {
        return $this->hasMany(Size::class,'size_id');
    }


}
