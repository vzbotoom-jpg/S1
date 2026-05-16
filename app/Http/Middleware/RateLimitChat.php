<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitChat
{
    public function __construct(private RateLimiter $limiter) {}

    public function handle(Request $request, Closure $next): Response
    {
        $key = 'chat:' . auth()->id();

        // Allow 30 messages per minute per user
        if ($this->limiter->tooManyAttempts($key, 30)) {
            $seconds = $this->limiter->availableIn($key);

            return response()->json([
                'message' => "Terlalu banyak pesan. Coba lagi dalam {$seconds} detik.",
                'retry_after' => $seconds,
            ], 429);
        }

        $this->limiter->hit($key, 60);

        return $next($request);
    }
}