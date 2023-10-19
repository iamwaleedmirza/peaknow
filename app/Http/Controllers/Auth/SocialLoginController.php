<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserLogs;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Twilio\MessageController;
use App\Http\Controllers\Utils\LocationCheckController;

class SocialLoginController extends Controller
{
    // public function redirectToProvider($provider)
    // {
    //     return Socialite::driver($provider)->redirect();
    // }

//     public function providerCallback($provider)
//     {
//         try {
//             $social_user = Socialite::driver($provider)->user();
//             $social_email = "";

//             $sAccount = SocialAccount::where([
//                 'provider' => $provider,
//                 'provider_id' => $social_user->getId()
//             ])->first();

//             if ($sAccount) {
//                 if ($sAccount->user->phone_verified != 1 && empty($sAccount->user->phone)) {
//                     auth()->login($sAccount->user);
//                     $isLocationLive = LocationCheckController::isLocationLive();

//                     if (!$isLocationLive) {
// //                        Session::flush();
// //                        Auth::logout();
//                         return redirect()->route('state-not-live');
//                     } else {
//                         User::where('id', auth()->user()->id)->update([
//                             'user_state_allowed' => true
//                         ]);
//                     }

//                     return redirect()->route('register-details')
//                         ->with(['warning' => 'Your phone number is not yet verified. Please verify it to use our services.']);

//                 } elseif ($sAccount->user->phone_verified != 1 && !empty($sAccount->user->phone)) {
//                     auth()->login($sAccount->user);

//                     $isLocationLive = LocationCheckController::isLocationLive();

//                     if (!$isLocationLive) {
// //                        Session::flush();
// //                        Auth::logout();
//                         return redirect()->route('state-not-live');
//                     } else {
//                         User::where('id', auth()->user()->id)->update([
//                             'user_state_allowed' => true
//                         ]);
//                     }
//                     $user = auth()->user();
//                     $message = new MessageController();
//                     $resendCount = UserLogs::where('user_id', $user->id)
//                     ->where('type', 'sentOtp')
//                     ->whereDate('created_at', date('Y-m-d'))
//                     ->orderBy('created_at', 'DESC')->get();
//                     if ($resendCount->count() !== 0) {
//                         $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
//                         $currentDate = strtotime(date('Y-m-d H:i:s'));
//                         if ($currentDate <= $resendDate) {
//                             return redirect()->route('otp-verify')->with('warning','Your Phone is not verified yet');
//                         }
//                     }
//                     UserLogs::where('user_id', $user->id)
//                     ->where('type', 'sentOtp')->delete();
//                     UserLogs::create([
//                             'type' => 'sentOtp',
//                             'description' => 'OTP has been sent successfully',
//                             'user_id' => Auth::user()->id
//                     ]);
//                     $message->sendVerifyOtp(Session::get('change-phone')?:$user->phone, Auth::user()->id);
//                     return redirect()->route('otp-verify')
//                         ->with(['warning' => 'Your phone number is not verified.']);
//                 }

//                 auth()->login($sAccount->user);
//                 if (Session::get('selected_plan_id') && !auth()->user()->getActiveOrder()) {
//                     return redirect()->route('medical-questions');
//                 }
//                 return redirect()->route('account-home');

//             } else {
//                 if ($social_user->getEmail() == null && Session::get('social-email') == null) {
//                     Session::put('email-required', '0');
//                     Session::put('social-provider');
//                     $fullName = explode(" ", $social_user->getName());
//                     Session::put('user', [
//                         'first_name' => $fullName[0],
//                         'last_name' => $fullName[1],
//                     ]);

//                     return redirect()->route('register.user')->with('warning', 'There is no any email address is associated with your Facebook account. You can use below registration form to create a Peakscurative account');

//                 } else {
//                     Session::put('email-required', '0');
//                     $social_email = Session::get('social-email');
//                     Session::put('social-email', '');
//                 }
//             }

//             $user = User::where('email', empty($social_email) ? $social_user->getEmail() : $social_email)->first();

//             if ($user) {
//                 $user->socialAccounts()->create([
//                     'provider' => $provider,
//                     'provider_id' => $social_user->getId()
//                 ]);
//                 auth()->login($user);
//                 if (Session::get('selected_plan_id') && !auth()->user()->getActiveOrder()) {
//                     return redirect()->route('medical-questions');
//                 }
//                 return redirect()->route('account-home');
//             }

//             $fullName = explode(" ", $social_user->getName());
//             Session::put('user', [
//                 'email' => empty($social_email) ? $social_user->getEmail() : $social_email,
//                 'first_name' => $fullName[0],
//                 'last_name' => $fullName[1],
//                 'telemedicine_agreement' => 1,
//                 'email_verified' => 1,
//             ]);

//             Session::put('userSocial', [
//                 'provider' => $provider,
//                 'provider_id' => $social_user->getId()
//             ]);

//             Cookie::queue(Cookie::make('firstTimeLogin', '1', 36000));

//             Session::flash('success', 'Welcome to PeaksCurative. Your account was created successfully.');
//             return redirect()->route('social-confirmation');

//         } catch (\Exception $e) {
//             return redirect()->route('login')->with('error', $e->getMessage());
//         }
//     }

//     public function registerSocialLogin()
//     {
//         try {
//             $social_userProvider = Session::get('userSocial');
//             $social_user = Session::get('user');
//             $social_email = "";

//             $user = User::where('email', empty($social_email) ? $social_user['email'] : $social_email)->first();

//             if ($user) {
//                 $user->socialAccounts()->create([
//                     'provider' => $social_userProvider['provider'],
//                     'provider_id' => $social_userProvider['provider_id']
//                 ]);
//                 auth()->login($user);
//                 if (Session::get('selected_plan_id') && !auth()->user()->getActiveOrder()) {
//                     return redirect()->route('medical-questions');
//                 }
//                 return redirect()->route('account-home');
//             }

//             $user = User::create([
//                 'email' => empty($social_email) ? $social_user['email'] : $social_email,
//                 'first_name' => $social_user['first_name'],
//                 'last_name' => $social_user['last_name'],
//                 'telemedicine_agreement' => 1,
//                 'email_verified' => 1,
//             ]);

//             $user->socialAccounts()->create([
//                 'provider' => $social_userProvider['provider'],
//                 'provider_id' => $social_userProvider['provider_id']
//             ]);
//             Cookie::queue(Cookie::make('firstTimeLogin', '1', 36000));
//             auth()->login($user);
//             $isLocationLive = LocationCheckController::isLocationLive();

//             if (!$isLocationLive) {
// //                Session::flush();
// //                Auth::logout();
//                 return redirect()->route('state-not-live');
//             } else {
//                 User::where('id', auth()->user()->id)->update([
//                     'user_state_allowed' => true
//                 ]);
//             }

//             Session::put('userSocial');
//             Session::put('user');
//             Session::flash('success', 'Welcome to PeaksCurative. Your account was created successfully.');
//             $userData = User::find(auth()->user()->id);
//             if ($userData->firstsiginup == 1) {
//                 $welcomeMailData = [
//                     'account_username' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
//                     'year' => now()->format('Y'),
//                     'action_url' => route('account-home')
//                 ];

//                 $mailResponse = SendGridController::sendMail(auth()->user()->email, TEMPLATE_ID_WELCOME_MAIL, $welcomeMailData, 'peakscurativeteam@peakscurative.com');
//             }
//             return redirect()->route('register-details');

//         } catch (\Exception $e) {
//             return redirect()->route('login')->with('error', $e->getMessage());
//         }
//     }

    // public function confirmSocialInfo()
    // {
    //     if (Session::get('user') !== null) {
    //         return view('auth.social-login-confirmation');
    //     } else {
    //         return redirect()->route('login');
    //     }
    // }

    // public function SetUserEmail(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //     ]);
    //     if ($validator->fails()) {
    //         return redirect()->route('login')->with('error', 'Email Is Incorrect!');
    //     }
    //     Session::put('social-email', $request->email);
    //     if (Session::get('social-provider')) {
    //         return $this->redirectToProvider(Session::get('social-provider'));
    //     } else {
    //         return redirect()->route('login')->with('error', 'No Provider Selected!');
    //     }

    // }

}
