<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class HandleUserInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('register')) {       
            $token = $request->query('token') ?? $request->token;
            
            $invitation = DB::table('invitation')->where('token',$token)->first();
            
            if(!$invitation) {
                return abort(403);
            }
            
            $user =  User::find($invitation->user_id);

            $request->merge([
                'email' => $user->email,
                'token' => $token]
            );
        }
        
        return $next($request);
    
    }
}
