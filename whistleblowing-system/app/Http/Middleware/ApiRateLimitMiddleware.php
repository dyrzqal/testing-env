<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAttempts = '60', string $decayMinutes = '1'): Response
    {
        $key = $this->resolveRequestSignature($request);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);
            
            return response()->json([
                'error' => 'Too many requests. Please try again later.',
                'retry_after' => $retryAfter
            ], 429)->header('Retry-After', $retryAfter);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers
        $response->headers->add([
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => RateLimiter::remaining($key, $maxAttempts),
            'X-RateLimit-Reset' => RateLimiter::availableIn($key) + time()
        ]);

        return $response;
    }

    /**
     * Resolve the request signature for rate limiting.
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $userId = $request->user()?->id ?? 'guest';
        $ip = $request->ip();
        $endpoint = $request->path();
        
        return sha1("rate_limit:{$userId}:{$ip}:{$endpoint}");
    }
}