<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Controller;
use App\Mail\PasswordResetSuccessMail;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = 'user/login';

    protected function resetPassword($user, $password)
    {
        $this->setUserPassword($user, $password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'year' => now()->format('Y'),
            'action_url' => route('login.user')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_PASSWORD_RESET_SUCCESS, $emailData, 'notify@peakscurative.com');

//        Mail::to($user->email)->send(new PasswordResetSuccessMail(['full_name' => $user->first_name . ' ' . $user->last_name]));

        session()->flash('success', 'Password reset successful.');
    }
}
