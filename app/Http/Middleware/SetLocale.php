<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session
        $locale = session('locale');
        
        // If no locale in session, set default to Turkish
        if (empty($locale)) {
            $locale = 'tr';
            session(['locale' => 'tr']);
        }
        
        // Validate locale (only allow tr and en)
        if (!in_array($locale, ['tr', 'en'])) {
            $locale = 'tr';
            session(['locale' => 'tr']);
        }
        
        // Set application locale
        app()->setLocale($locale);
        
        return $next($request);
    }
}
