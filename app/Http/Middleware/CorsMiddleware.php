<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Permettre l'accès depuis n'importe quelle origine
        // Remplace "*" par une URL spécifique si tu veux limiter l'accès à certaines origines
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*') // Autoriser toutes les origines
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS') // Autoriser ces méthodes HTTP
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Authorization'); // Autoriser ces headers
    }
}
