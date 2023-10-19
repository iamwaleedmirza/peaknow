<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PeaksToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->header('Authorization')) {
            $authHeader = json_decode($request->header('Authorization'));

            if ($authHeader->authorization == env('PEAKS_API_ORDER_TOKEN')) {
                $allowedHosts = explode(',', env('ALLOWED_DOMAINS'));
                $requestHost = $authHeader->site_url;

                if (!in_array($requestHost, $allowedHosts)) {
                    $requestInfo = [
                        'host' => $requestHost,
                        'ip' => $request->getClientIp(),
                    ];

                    return response()->json([
                        'message' => 'Not a valid API request.',
                        'data' => $requestInfo
                    ], 401);
                }

                return $next($request);
            }
        }

        return response()->json([
            'message' => 'Not a valid API request.'
        ], 401);
    }
}
