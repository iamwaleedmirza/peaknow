<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Auth;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);
        $permissionArray = [];
        $userRole = Role::where('id',Auth::user()->role_id)->first();
        if($userRole){
            $userRole->rolePermissions = $userRole->permissions;
            foreach ($userRole->rolePermissions as $value) {
                // print_r($value);exit;
                 $permissionArray[] = $value->name;
            }
        }


        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if (! is_null($permission)) {
            $permissions = is_array($permission)
                ? $permission
                : explode('|', $permission);
        }

        if ( is_null($permission) ) {
            $permission = $request->route()->getName();
            $permissions = array($permission);
        }
        if(Auth::user()->u_type=='superadmin' || (in_array($request->route()->getName(), $permissionArray) || (\Request::route()->getName()=='admin.change-password' || \Request::route()->getName()=='admin.change-password-post' || \Request::route()->getName()=='admin.home'))) {
            return $next($request);
        } else {
            throw UnauthorizedException::forPermissions($permissions);
        }
        
        
        // print_r($authGuard->user());exit;
        
        // foreach ($permissions as $permission) {
        //     if ($authGuard->user()->can($permission)) {
        //         return $next($request);
        //     }
        // }

        // throw UnauthorizedException::forPermissions($permissions);
    }
}