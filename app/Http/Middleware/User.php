<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class User
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
        $user_type = Auth::user()->u_type;
        //Added Admin Type to middle user side
        if($user_type == 'patient' || $user_type ==  'superadmin' || $user_type ==  'admin'){
            return $next($request);
        }
        else{
            abort('403','UnAuthorized Access');
        }
    }
}
