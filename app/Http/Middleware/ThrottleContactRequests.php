<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * 5 submissions per minute per IP. It uses its own key so it never mixes with the general API throttle.
 */
final class ThrottleContactRequests
{
    private const MAX_ATTEMPTS = 5;

    private const DECAY_SECONDS = 60;

    public function handle(Request $request, Closure $next): Response
    {
        $key = 'contact-requests:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            throw new ThrottleRequestsException(
                'Too Many Attempts.',
                null,
                ['Retry-After' => (string) RateLimiter::availableIn($key)],
            );
        }

        RateLimiter::hit($key, self::DECAY_SECONDS);

        return $next($request);
    }
}
