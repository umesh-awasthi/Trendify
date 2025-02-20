<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentAuthMiddleware
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
        // Check if user is authenticated and is an agent
        if (Auth::check() && Auth::user()->isAgent()) {
            return $next($request);
        }

        // Redirect to login if not authenticated or not an agent
        return redirect()->route('login')->with('error', 'Unauthorized access');
    }
}
