<?php

namespace App\Http\Middleware;

use App\Models\ProjectConfig;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class LucentAuth
 * @package App\Http\Middleware\LucentAuth
 * 
 * @author Muhammad Imran Israr (mimranisrar6@gmail.com)
 */
class LucentAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->checkKey($request->headers->all());

        return $next($request);
    }

    /**
     * Check the validity of the Lucent key in the headers.
     *
     * @param array $headers The headers array.
     * @return void
     * @throws UnauthorizedHttpException Thrown when the Lucent key is missing or invalid.
     */
    private function checkKey(array $headers): void 
    {
        if (!array_key_exists('authorization', $headers)) {
            throw new UnauthorizedHttpException('lucent-auth', 'Lucent key not found');
        }

        if (!$this->validateKey($headers)) {
            throw new UnauthorizedHttpException('project-not-found', 'Project with given key not found');
        }
    }

    /**
     * Validate the project key from the authorization headers.
     *
     * @param array $headers The headers array from the request.
     * @return bool Returns `true` if the project key is valid, otherwise `false`.
     */
    private function validateKey(array $headers): bool 
    {
        // getting project key from authorization
        $token = $this->getToken($headers['authorization'][0]);

        // getting project config against project private key
        $projectConfig = ProjectConfig::where('values->key', $token)->first();

        if (!$projectConfig) {
            return false;
        }

        request()->merge(['project_id' => $projectConfig->project_id]);
        return true;

    }

    /**
     * Extracts the token from an authorization header.
     *
     * @param string $authorizationHeader The authorization header in the format "Bearer {token}".
     * @return string The extracted token.
     */
    private function getToken(string $authorizationHeader): string 
    {
        return explode(' ', $authorizationHeader)[1];
    }

}
