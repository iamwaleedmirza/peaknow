<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckForActiveOrder
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
        if (!Session::get('change-plan') && Session::get('selected_plan_id') && auth()->user()->getActiveOrder()) {
            return redirect()->route('account-home');
        }elseif(!Session::get('selected_plan_id')){
            return redirect()->route('account-home');
        }else{
            return $next($request);
        }

    }
}
