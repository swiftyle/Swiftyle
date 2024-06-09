<?php

// App/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MainCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'description', 'main_categories_id'
    ];

    /**
     * Boot method for the model.
     */
    
    
    public function subcategories()
    {
        return $this->hasMany(SubCategory::class, 'sub_categories', 'id');
    }
}
