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
        'stock',
    ];

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'size_color');
    }
}
