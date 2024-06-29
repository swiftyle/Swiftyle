<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeColor extends Model
{
    use HasFactory;
    protected $table ='size_color';
    protected $fillable = [
       'size_id',
        'color_id',
        'stock'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
    public function size()
    {
        return $this->belongsTo(Size::class);
    }
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}
