<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'couriers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'logo',
        'courier_categories_id',
        'modified_by'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    /**
     * Get the category that owns the courier.
     */
    public function category()
    {
        // Gunakan belongsTo jika setiap kurir hanya terkait dengan satu kategori
        return $this->belongsTo(CourierCategory::class, 'courier_categories_id');
    }

    public function checkout()
    {
        // Gunakan hasMany jika setiap checkout terkait dengan satu kurir
        return $this->hasMany(Checkout::class, 'courier_id');
    }
}
