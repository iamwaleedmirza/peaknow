<?php

namespace App\Http\Middleware;

use Closure;

class SiteSetting
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $site_setting = \DB::table('general_settings')->first();
        \View::share('site_setting',$site_setting);
        return $next($request);
    }
}
