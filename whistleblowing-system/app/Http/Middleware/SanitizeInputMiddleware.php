<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();
        
        array_walk_recursive($input, function (&$input) {
            if (is_string($input)) {
                // Remove null bytes
                $input = str_replace("\0", '', $input);
                
                // Trim whitespace
                $input = trim($input);
                
                // Remove potential XSS vectors for non-HTML fields
                if (!$this->isHtmlField($input)) {
                    $input = strip_tags($input);
                }
                
                // Remove suspicious patterns
                $input = preg_replace('/javascript:/i', '', $input);
                $input = preg_replace('/on\w+\s*=/i', '', $input);
                $input = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $input);
                $input = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '', $input);
            }
        });

        // Merge sanitized input back into request
        $request->merge($input);

        return $next($request);
    }

    /**
     * Determine if a field should allow HTML content.
     */
    protected function isHtmlField(string $input): bool
    {
        // Define fields that are allowed to contain HTML
        $htmlFields = [
            'description', 
            'evidence_description', 
            'resolution_details',
            'comment'
        ];

        // Check if current request has any HTML fields
        // This is a simple check - you might want to make this more sophisticated
        return false; // For now, we'll strip HTML from all fields for security
    }

    /**
     * Check if input contains suspicious patterns.
     */
    protected function containsSuspiciousPatterns(string $input): bool
    {
        $suspiciousPatterns = [
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<script/i',
            '/<iframe/i',
            '/eval\s*\(/i',
            '/expression\s*\(/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }
}