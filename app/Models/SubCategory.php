<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'name', 'description', 'main_category_id', 'modified_by'
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }

    /**
     * Get the main category that owns the sub-category.
     */
    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_category_id');
    }
}
