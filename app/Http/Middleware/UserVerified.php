<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\UserLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Twilio\MessageController;

class UserVerified
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
        $user = Auth::user();
        $message = new MessageController();
        if($user->phone_verified == 0 && $user->firstsiginup == 1 && empty($user->phone) && empty(Session::get('change-phone'))){
            return redirect()->route('register-details')
            ->with(['warning' => 'Your phone number is not yet verified. Please verify it to use our services.']);
        }elseif(Session::get('otp-send') == 1){
            return redirect()->route('user.register_successful');
        }elseif($user->phone_verified == 0 && $user->firstsiginup == 1){
            $resendCount = UserLogs::where('user_id', $user->id)
                ->where('type', 'sentOtp')
                ->whereDate('created_at', date('Y-m-d'))
                ->orderBy('created_at', 'DESC')->get();
                if ($resendCount->count() !== 0) {
                    $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
                    $currentDate = strtotime(date('Y-m-d H:i:s'));
                    if ($currentDate <= $resendDate) {
                        return redirect()->route('otp-verify')->with('warning','Your Phone is not verified yet');
                    }
                }
                UserLogs::where('user_id', $user->id)
                ->where('type', 'sentOtp')->delete();
                UserLogs::create([
                        'type' => 'sentOtp',
                        'description' => 'OTP has been sent successfully',
                        'user_id' => Auth::user()->id
                ]);
            $message->sendVerifyOtp(Session::get('change-phone')?:$user->phone, Auth::user()->id);
            return redirect()->route('otp-verify')->with('warning','Your Phone is not verified yet');
        }elseif($user->email_verified == 0 && $user->firstsiginup == 1){
            $resendCount = UserLogs::where('user_id', $user->id)
            ->where('type', 'sentEmailOtp')
            ->whereDate('created_at', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')->get();
            if ($resendCount->count() !== 0) {
                $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
                $currentDate = strtotime(date('Y-m-d H:i:s'));

                if ($currentDate <= $resendDate) {
                    return redirect()->route('email-verify')->with('warning','Your Email is not verified yet');
                }
            }
            UserLogs::where('user_id', $user->id)
            ->where('type', 'sentEmailOtp')->delete();
            UserLogs::create([
                    'type' => 'sentEmailOtp',
                    'description' => 'Email otp has been sent successfully',
                    'user_id' => Auth::user()->id
            ]);
            $message->sendVerifyEmailOtp(Session::get('change-email')?:$user->email, Auth::user()->id);
            return redirect()->route('email-verify')->with('warning','Your Email is not verified yet');
        }else{
            return $next($request);
        }
        
    }
}
