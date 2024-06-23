<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourierCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'courier_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'courier_costs',
        'modified_by',
    ];
    protected $dates = ['deleted_at, created_at, updated_at'];
    public function courierCategories()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id');
    }
}
