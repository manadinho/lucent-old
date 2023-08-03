<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LucentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->checkKey(request()->headers->all());


        // fast(request()->headers->all());
        return $next($request);
    }

    private function checkKey(array $headers): void 
    {
        if(!array_key_exists('lucent_key', $headers)) {
            throw new UnauthorizedHttpException('lucent-auth', 'Lucent key not found');
        }
    }

    private function validateKey() 
    {
        // todo::we will implement after projects implementation
    }
}
