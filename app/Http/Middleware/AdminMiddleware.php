<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check()) {
            if (\Illuminate\Support\Facades\Auth::user()->role === 'admin') {
                return $next($request);
            }
            abort(403, 'Unauthorized access. Your role is "' . \Illuminate\Support\Facades\Auth::user()->role . '", but access requires "admin".');
        }

        abort(403, 'Unauthorized access. You are not logged in.');
    }
}
