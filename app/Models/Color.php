<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $table = 'colors';
    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'size_color')
                    ->withPivot('stock')
                    ->withTimestamps();
    }
}
