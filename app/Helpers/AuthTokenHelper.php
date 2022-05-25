<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\AuthToken;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthTokenHelper
{
    public function createToken(User $user, $tokenType)
    {
        return AuthToken::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $tokenType,
            ],
            [
                'token' => Str::random(100),
            ]
        );
    }

    public function validateToken(string $token, string $tokenType)
    {
        $token = AuthToken::where('token', $token)
            ->where('type', $tokenType)
            ->first();

        if (!$token) {
            throw ValidationException::withMessages([
                'token' => 'The provided token is invalid.'
            ]);
        }

        if ($token->expired()) {
            throw ValidationException::withMessages([
                'token' => 'The token has expired. Please request a new token.'
            ]);
        }

        return $token;
    }
}