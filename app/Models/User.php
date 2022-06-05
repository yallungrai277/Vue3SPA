<?php

namespace App\Models;

use App\Traits\PrimaryKeyUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, PrimaryKeyUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function booted()
    {
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

    public function passwordResetToken()
    {
        return $this->hasOne(AuthToken::class)
            ->where('type', AuthToken::PASSWORD_RESET_TOKEN);
    }

    public function verifyEmailToken()
    {
        return $this->hasOne(AuthToken::class)
            ->where('type', AuthToken::VERIFY_EMAIL_TOKEN);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin()
    {
        return $this->roles()
            ->where('name', Role::ADMIN_ROLE)
            ->exists($this->id);
    }

    public function isUser()
    {
        return $this->roles()
            ->where('name', Role::USER_ROLE)
            ->exists($this->id);
    }
}