<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class Admin
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

        $userRole = Role::get()->pluck('name')->toArray();
        $userRole[] = 'superadmin';
        $user_type = Auth::user()->u_type;
        if (in_array($user_type, $userRole)) {
            return $next($request);
        }
        else{
            abort('403','UnAuthorized Access');
        }
    }
}
