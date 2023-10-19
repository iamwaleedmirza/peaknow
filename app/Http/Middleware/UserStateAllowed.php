<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserStateAllowed
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()) {
            if (auth()->user()->user_state_allowed == 0) {
                return redirect()->route('state-not-live');
            } else {
                return $next($request);
            }
        }

        return redirect()->route('login.user');
    }
}
