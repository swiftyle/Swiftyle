<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['uuid','product_uuid', 'size', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }
    /**
     * Boot method for the model.
     */

}
