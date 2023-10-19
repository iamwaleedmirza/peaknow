<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowDomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $request_token = @$request->header()['token'][0];
        $token = env('WP_API_TOKEN');
        if($request_token==$token){
            return $next($request);
        } else {
            return response()->json(['status' => false,'message' => 'You are not allow to access this api'], 200);
        }
    }
}
