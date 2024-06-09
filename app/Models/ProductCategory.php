<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_category';
    protected $fillable = [
        'product_id',
        'sub_category_id'];

        public function product(){
            return $this->hasMany(Product::class, 'product_id');
        }
        public function category(){
            return $this->hasMany(SubCategory::class, 'sub_category_id');
        }
}
