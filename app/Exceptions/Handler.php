<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\URL;
use Throwable;
use Sentry\Laravel\Integration;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            Integration::captureUnhandledException($e);
        });
    }

//    public function render($request, Throwable $e)
//    {
//        if ($e instanceof TokenMismatchException) {
//            if (str_contains(URL::current(), 'admin')) {
//                return redirect()->route('admin.login');
//            } else {
//                return redirect()->route('login.user');
//            }
//        }
//    }
}
