<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // return redirect()->route('login');
        // print_r(Auth::guard('customer')->check());
        // die("dfgdfg");
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
