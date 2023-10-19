<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use TimeHunter\LaravelGoogleReCaptchaV3\Facades\GoogleReCaptchaV3;

class UserLoginController extends Controller
{

    public function login(Request $request)
    {


        if ((env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')) {
            if (GoogleReCaptchaV3::verifyResponse(
                    $request->input('g-recaptcha-response')
                )->isSuccess() == false
            ) {
                throw ValidationException::withMessages([
                    'email' => 'Captcha verification failed. Please refresh your page and try again.'
                ]);
            }
        }

        $attributes = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($attributes)) {
            session()->regenerate();

            if (auth()->user()->account_status == 0) {
                Auth::logout();
                session()->flush();
                throw ValidationException::withMessages([
                    'email' => 'Your account has been disabled. Please contact to administrator.'
                ]);
            }
            if (auth()->user()->user_state_allowed == 0) {
                // Session::flush();
                // Auth::logout();
                return redirect()->route('state-not-live');
            }
            if (Session::get('selected_plan_id') && !auth()->user()->getActiveOrder()) {
                return redirect()->route('medical-questions');
            }
            return redirect()->intended(route('account-home'));
        }

        throw ValidationException::withMessages([
            'email' => 'Email or password is invalid.'
        ]);
    }

    public function adminLogin(Request $request)
    {
        if ((env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')) {
            if (GoogleReCaptchaV3::verifyResponse(
                    $request->input('g-recaptcha-response')
                )->isSuccess() == false
            ) {
                throw ValidationException::withMessages([
                    'email' => 'Captcha verification failed. Please refresh your page and try again.'
                ]);
            }
        }
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $user_exists = User::where('email', $request->email)->where('u_type','!=', 'patient')->first();
        if ($user_exists) {
            if ($request->password && Hash::check($request->password, $user_exists->password)) {
                Auth::loginUsingId($user_exists->id);
                return redirect()->intended(route('admin.home'));
            } else {
                return back()->withInput()->withErrors(['email' => 'Email/password is invalid.']);
            }
        } else {
            return back()->withInput()->withErrors(['email' => 'Email/password is invalid.']);
        }
    }

    public function emailVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verify_hash' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect()->route('email-verify')
                ->withErrors($validator);
        }
        $user = User::where('email_hash', $request->verify_hash)->first();
        $user->email_verified = 1;
        $user->save();
        return redirect()->route('email-verify')
            ->with('success', 'Your Email has been Verified!!');
    }

    public function logout()
    {
        if (Auth::check()) {
            $prefix = strtolower(Auth::user()->u_type);

            Auth::logout();
            session()->flush();

            if ($prefix == 'admin') {
                $uri = route('admin.login');
            } else {
                $uri = route('login.user');
            }
            return redirect($uri);

        } else {
            if (str_contains(URL::current(), 'admin')) {
                return redirect()->route('admin.login');
            } else {
                return redirect()->route('login.user');
            }
        }
    }

    public function AdminLogout()
    {
        if (Auth::check()) {
            $prefix = strtolower(Auth::user()->u_type);
            Auth::logout();
            session()->flush();

            $uri = route('admin.login');
            return redirect($uri);
        } else {
            return redirect()->route('admin.login');
        }
    }
}
