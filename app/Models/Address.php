<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Country;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id', 'user_id', 'firstname', 'lastname', 'street', 'city', 'province', 'district', 'country', 'postal_code', 'house_number', 'apartment_number', 'phone_number', 'type', 'primary'
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!Country::isValid($model->country)) {
                throw ValidationException::withMessages(['country' => 'Invalid country value.']);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
