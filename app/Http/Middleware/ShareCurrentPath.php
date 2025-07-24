<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareCurrentPath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share the current path with all Inertia responses
        Inertia::share([
            'currentPath' => $request->getPathInfo(),
            'currentUrl' => $request->url(),
        ]);

        return $next($request);
    }
}
