<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class ThrottleRequestsMiddleware extends ThrottleRequests
{
    protected function buildException($request, $key, $maxAttempts, $responseCallback = null)
    {
        $retryAfter = $this->getTimeUntilNextRetry($key);

        $headers = $this->getHeaders(
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts, $retryAfter),
            $retryAfter
        );

        return is_callable($responseCallback)
            ? new HttpResponseException($responseCallback($request, $headers))
            : new ThrottleRequestsException('Too Many Attempts. Please retry after ' . $retryAfter . ' seconds.', null, $headers);
    }
}