<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logo',
        'courier_categories_id',
    ];

    /**
     * Get the category that owns the courier.
     */
    public function category()
    {
        return $this->hasMany(CourierCategory::class, 'courier_categories_id');
    }
}
