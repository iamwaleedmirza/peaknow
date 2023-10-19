<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Spatie\RobotsMiddleware\RobotsMiddleware;

class MyRobotsMiddleware extends RobotsMiddleware
{
    protected function shouldIndex(Request $request)
    {
        return 'noindex';
        // if (config('app.env') === 'production') {
        //     if ($request->segment(1) == 'admin') {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('state-not-live')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('continue-state-not-live')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('user.register_successful')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('register-details')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('user.register.second-step')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('user.verifyOtpByType')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('social-redirect', 'google')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('social-redirect', 'facebook')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('social-callback', 'google')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('social-callback', 'facebook')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('social-confirmation')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('register-social-user')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('password-reset')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('set.social.email')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('authorize.iframe')) {
        //         return 'noindex';
        //     }
        //     // if ($request->url() == route('get.plans')) {
        //     //     return 'noindex';
        //     // }
        //     if (str_contains($request->url(), 'subscribe/plan')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('unsubscribe.plan')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('cancel.plan')) {
        //         return 'noindex';
        //     }
        //     if ($request->url() == route('user.logout')) {
        //         return 'noindex';
        //     }
        //     if (str_contains($request->url(), 'storage')) {
        //         return 'noindex';
        //     }

        //     return 'all';

        // } else {
        //     return 'noindex';
        // }
    }
}
