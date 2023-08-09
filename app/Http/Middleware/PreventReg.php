<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class PreventReg
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //dd( $request->query('token')??$request->token);
        if($request->is('register'))
        {       $token = $request->query('token')??$request->token;
                $invitation = DB::table('invitation')->where('token',$token)->first();
 
                if(!$invitation)
                     return abort(403);
                        $user =  User::where('id',$invitation->user_id)->first();
                        $request->merge(['email'=>$user->email,'token'=>$token]);
                      // dd($user);
        }
        return $next($request);
    
    
    }
}
