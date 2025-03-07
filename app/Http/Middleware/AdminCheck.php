<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return redirect('login');
        }

        // Allow admin users to proceed
        if ($request->user()->usertype === 'admin') {
            return $next($request);
        }

        // Redirect other user types appropriately
        if ($request->user()->usertype === 'user') {
            return redirect('dashboard');
        }

        // Default fallback for any other user types
        return redirect('dashboard');
    }
}
