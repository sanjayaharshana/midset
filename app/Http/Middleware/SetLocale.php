<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        // Get locale from session or default to 'en'
        $locale = Session::get('locale', config('app.locale'));
        
        // Validate locale (only allow 'en' and 'si')
        if (!in_array($locale, ['en', 'si'])) {
            $locale = 'en';
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        return $next($request);
    }
}
