<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStyle extends Model
{
    use HasFactory;

    protected $table = 'product_style';
    protected $fillable = ['product_id','style_id'];
    protected $hidden = ['created_at', 'updated_at'];
    protected $casts = [
        'product_id' => 'integer',
       'style_id' => 'integer',
    ];
    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_id');
    }
    public function style()
    {
        return $this->belongsToMany(Style::class, 'style_id');
    }
}
