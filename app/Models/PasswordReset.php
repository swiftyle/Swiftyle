<?php

namespace App\Models;

use App\Helpers\UuidHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

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

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('phone', '=', $this->getAttribute('phone'))
            ->where('email', '=', $this->getAttribute('email'));

        return $query;
    }

}
