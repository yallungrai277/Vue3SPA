<?php

namespace App\Helpers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RateLimitingValidationHelper
{
    private $throttleKey;

    private $perMinute;

    public function __construct($throttleKey, $perMinute)
    {
        $this->throttleKey = $throttleKey;
        $this->perMinute = $perMinute;
    }

    public function throwValidationExceptionIfRateExceedsLimited(string $validationKey)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey, $this->perMinute)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey);
        throw ValidationException::withMessages([
            $validationKey => 'Too many attempts. Please try again in ' . ceil($seconds / 60)
        ]);
    }

    public function hit()
    {
        RateLimiter::hit($this->throttleKey);
    }
}