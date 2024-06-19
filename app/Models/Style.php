<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Style extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'image',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_style');
    }

    public function users()
    { 
        return $this->belongsToMany(User::class, 'preferences');
    }
}
