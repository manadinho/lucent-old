<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNameMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && empty(auth()->user()->name) && (!in_array($request->route()->getName(), ['profile.edit', 'profile.update']))) {
            // set intended url for redirecting after profile update
            app('redirect')->setIntendedUrl(route($request->route()->getName()));

            // redirect to profile edit
            return redirect()->route('profile.edit');
        }
    
        return $next($request);
    }
}
