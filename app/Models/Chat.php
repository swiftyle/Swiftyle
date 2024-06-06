<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user1_id',
        'user2_id',
    ];

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id', 'id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id', 'id');
    }

}
