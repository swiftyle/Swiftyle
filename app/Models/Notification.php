<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
