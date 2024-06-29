<?php

// App/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainCategory extends Model
{
    use HasFactory, SoftDeletes;

  
    protected $table = 'main_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    
     protected $fillable = [
        'name', 'description', 'modified_by'];
    
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    /**
     * Boot method for the model.
     */
    
    
    public function subcategories()
    {
        return $this->belongsTo(SubCategory::class, 'sub_categories', 'id');
    }
}
