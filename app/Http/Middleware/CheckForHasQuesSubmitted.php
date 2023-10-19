<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForHasQuesSubmitted
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
        if (!session()->has('questionare_data')) {
            return redirect()->route('account-home');
        }else{
            return $next($request);
        }
    }
}
