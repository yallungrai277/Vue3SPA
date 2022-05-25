<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    const TOKEN_VALID_FOR = 1;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'token',
    ];

    const PASSWORD_RESET_TOKEN = 'password_reset';
    const VERIFY_EMAIL_TOKEN = 'verify_email';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expired()
    {
        return round(now()->floatDiffInHours($this->updated_at), 2) > self::TOKEN_VALID_FOR;
    }
}