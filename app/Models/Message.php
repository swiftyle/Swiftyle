<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $table ='messages';
    protected $fillable = [
        'chat_id',
        'content',
        'status',
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
