<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminCheckMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if ($request->user() && $request->user()->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }
}
