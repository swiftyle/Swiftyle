<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasswordReset extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;
    protected $primaryKey = ['phone', 'email'];
    public $incrementing = false;

    protected $fillable = [
        'phone',
        'email',
        'otp_code',
        'otp_expiry',
        'email_token',
        'email_token_expiry',
        'created_at',
    ];

    protected $casts = [
        'otp_expiry' => 'datetime',
        'email_token_expiry' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $hidden = [
        'created_at', 'updated_at','deleted_at'
    ];
    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('phone', '=', $this->getAttribute('phone'))
            ->where('email', '=', $this->getAttribute('email'));

        return $query;
    }

}
