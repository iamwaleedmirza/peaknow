<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\QuesAnsRequest;
use App\Models\OrderSubscription;
use App\Models\{User,NewPlan,BelugaOrderDetail};
use App\Models\Order;
use App\Models\Plans;
use App\Models\UserLogs;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Twilio\MessageController;
use App\Http\Controllers\Api\SmartDoctorsApiController;
use App\Http\Controllers\Utils\LocationCheckController;
use TimeHunter\LaravelGoogleReCaptchaV3\Facades\GoogleReCaptchaV3;

class RegistrationController extends Controller
{
    public $shippingCost = 0;
    public function __construct() {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
    }
    public function registerFirstStep(Request $request)
    {
        if ((env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')) {
            if (GoogleReCaptchaV3::verifyResponse(
                    $request->input('g-recaptcha-response')
                )->isSuccess() == false
            ) {
                throw ValidationException::withMessages([
                    'captcha' => 'Captcha verification failed. Please refresh your page and try again.'
                ]);
            }
        }

        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'phone:US'],
            'full_number' => ['required', 'phone:US'],
            'agreement' => ['required']
        ], ['phone.exists' => 'The phone has already been taken.']);
        $checkPhone = User::where('phone', $request->full_number)->first();
        if ($checkPhone && $checkPhone->phone) {
            throw ValidationException::withMessages(['phone' => 'This phone number already exists. Please try with new phone number.']);
        }
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->full_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telemedicine_agreement' => $request->agreement
        ]);

        auth()->loginUsingId($user->id);

        Session::put('change-phone', $request->full_number);
        Session::put('otp-send', '1');
        Cookie::queue(Cookie::make('firstTimeLogin', '1', 36000));

        $isLocationLive = LocationCheckController::isLocationLive();

        if ($isLocationLive) {
            User::where('id', auth()->user()->id)->update([
                'user_state_allowed' => true
            ]);

            if (auth()->user()->phone_verified == 0) {
                $uri = route('user.register_successful');
            } else {
                $uri = route('rx-user-agreement');
            }

        } else {
            // Session::flush();
            // Auth::logout();
            $uri = route('state-not-live');
        }

//        $welcomeMailData = [
//            'account_username' => $user->first_name . ' ' . $user->last_name,
//            'year' => now()->format('Y'),
//            'action_url' => route('account-home')
//        ];
//        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_WELCOME_MAIL, $welcomeMailData);

//        Mail::to($user->email)->send(new WelcomeMail([
//            'full_name' => $user->first_name . ' ' . $user->last_name
//        ]));

        return redirect($uri);
    }

    public function registrationSuccessful()
    {
        return view('auth.registration-successful');
    }

    public function postRegistrationSuccessful()
    {
        if (auth()->user()->phone_verified == 0) {
            $user = auth()->user();
            $resendCount = UserLogs::where('user_id', $user->id)
                ->where('type', 'sentOtp')
                ->whereDate('created_at', date('Y-m-d'))
                ->orderBy('created_at', 'DESC')->get();

            if ($resendCount->count() !== 0) {
                $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                if ($currentDate >= $resendDate) {
                    return redirect()->route('otp-verify');
                }
                if ($resendCount->count() >= 3) {
                    session::flash('warning', 'Your phone number verification usage limit exceeded. Please try after 10 minutes.');
                    return redirect()->route('otp-verify');
                }
            }
            if (session::get('change-phone')) {
                $message = new MessageController();
                $response = $message->sendVerifyOtp(session::get('change-phone'), $user->id);
                if (!$response) {
                    Session::flash('warning', 'Something went wrong! Please try again later.');
                    return redirect()->route('otp-verify');
                }
            }elseif ($user->phone) {
                $message = new MessageController();
                $response = $message->sendVerifyOtp($user->phone, $user->id);
                if (!$response) {
                    Session::flash('warning', 'Something went wrong! Please try again later.');
                    return redirect()->route('otp-verify');
                }
            }
            UserLogs::create([
                'type' => 'sentOtp',
                'description' => 'OTP has been sent successfully',
                'user_id' => Auth::user()->id
            ]);
            Session::put('otp-send', '0');
            Session::flash('success', 'Phone verification code has been sent to your registered phone number.');
            return redirect()->route('otp-verify');
        } else {
            return redirect()->route('account-home');
        }
    }

    public function registerAjaxStep(Request $request)
    {
        if ((env('APP_ENV') == 'production' || env('APP_ENV') == 'staging')) {
            if (GoogleReCaptchaV3::verifyResponse(
                    $request->input('g-recaptcha-response')
                )->isSuccess() == false
            ) {
                throw ValidationException::withMessages([
                    'email' => 'Google reCaptcha Didnt Verified!!'
                ]);
            }
        }
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string'],
            'agreement' => ['required']

        ]);
        if ($validator->fails()) {

            return Response::json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 400);
        }

        $email_otp = rand(100001, 999999);
        $user = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'telemedicine_agreement' => $request->agreement

        ];
        $user = User::create($user);

        Auth::loginUsingId($user->id);
        // send otp to this user phone
        $message = new MessageController();
        $response = $message->sendVerifyOtp($request->phone, Auth::user()->id);
        if (!$response) {
            return Response::json(array(
                'success' => true,
                'message' => 'Something went wrong! Please try again later.',

            ), 400);
        }
        $isLocationLive = LocationCheckController::isLocationLive();
        if (!$isLocationLive) {
//            Session::flush();
//            Auth::logout();
            $uri = route('state-not-live');
        } else {
            User::where('id', auth()->user()->id)->update([
                'user_state_allowed' => true
            ]);

            if (Auth::user()->phone_verified == 0) {
                $uri = route('otp-verify');
            } else {
                $uri = route('rx-user-agreement');
            }

        }
        return Response::json(array(
            'success' => true,
            'message' => 'Account is successfully created',
            'uri' => $uri

        ), 200);

    }

    public function VerifyOtpByType(Request $request)
    {
        if ($request->type == 'email') {
            $validator = Validator::make($request->all(), [
                'type' => ['required', 'string'],
                'emailVerifyPin.*' => ['required', 'numeric'],

            ],['emailVerifyPin.*.required'=>'Verification code is required.']);
            if ($validator->fails()) {

                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()

                ), 400);
            }

            $inputCode = implode("", $request->emailVerifyPin);
            if (Session::get('change-email')) {
                $userData = User::where('id', Auth::user()->id)->first();
            } else {
                $userData = User::where('id', Auth::user()->id)->where('email_verified', 0)->first();
            }

            if (!isset($userData)) {


                return Response::json(array(
                    'success' => false,
                    'errors' => ['Your email address is already verified.'],
                    'uri' => route('account-home')

                ), 400);

            }
            $message = new MessageController();
            $verifyOtp = $message->verifyOtp($inputCode,Session::get('change-email')?Session::get('change-email'):Auth::user()->email, Auth::user()->id);
            if ($verifyOtp) {
                if (Session::get('change-email')) {
                    $userData->email = Session::get('change-email');
                    Session::put('change-email');
                }

                $userData->email_verified = 1;

                $uri = route('rx-user-agreement');
                if ($userData->firstsiginup == 1) {
                    $welcomeMailData = [
                        'account_username' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                        'year' => now()->format('Y'),
                        'action_url' => route('account-home')
                    ];

                    $mailResponse = SendGridController::sendMail(auth()->user()->email, TEMPLATE_ID_WELCOME_MAIL, $welcomeMailData, 'peakscurativeteam@peakscurative.com');
                }
                if ($userData->phone_verified == 1 && $userData->firstsiginup !== 1) {
                    $uri = route('account-home');

                } else {
                    $userData->firstsiginup = 0;

                }
                $userData->save();


                return Response::json(array(
                    'success' => true,
                    'message' => 'Your email address has been verified successfully.',
                    'uri' => $uri,

                ), 200);
            } else {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Incorrect verification code. Please try again.']

                ), 400);
            }
        }
        if ($request->type == 'phone') {
            $validator = Validator::make($request->all(), [
                'type' => ['required', 'string'],
                'phoneVerifyPin.*' => ['required', 'numeric'],

            ],['phoneVerifyPin.*.required'=>'Verification code is required']);
            if ($validator->fails()) {

                return Response::json(array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()

                ), 400);
            }
            $inputCode = implode("", $request->phoneVerifyPin);
            if (Session::get('change-phone')) {
                $userData = User::where('id', Auth::user()->id)->first();
            } else {
                $userData = User::where('id', Auth::user()->id)->where('phone_verified', 0)->first();
            }
            if (!isset($userData)) {

                return Response::json(array(
                    'success' => false,
                    'errors' => ['Your Phone number is already verified.'],
                    'uri' => route('account-home')

                ), 400);

            }
            $message = new MessageController();
            $verifyOtp = $message->verifyOtp($inputCode,Session::get('change-phone')?Session::get('change-phone'):Auth::user()->phone, Auth::user()->id);
            if ($verifyOtp) {
                if (Session::get('change-liberty-phone') == 1) {
                    $accountHome = new HomeController();
                    $libertyAPIResponse = $accountHome->checkAndUpdateLibertyBio(Auth::user(), Session::get('change-phone'));
                    Session::put('change-liberty-phone');
                    if ($libertyAPIResponse !== 200) {
                        //TODO -- Change the response on getting error from liberty
                        return Response::json([
                            'error_title' => Error_3001_Title,
                            'error_description' => Error_3001_Description,
                        ], 400);
                        Log::debug('Liberty Error: '.json_encode($libertyAPIResponse));
                    }
                    $uri = route('account-home');
                    return Response::json(array(
                        'success' => true,
                        'message' => 'Your Phone Number has been verified successfully.',
                        'uri' => $uri,

                    ), 200);
                } else {
                    if (Session::get('change-phone')) {
                        $userData->phone = Session::get('change-phone');
                        Session::put('change-phone');
                    }

                    $userData->phone_verified = 1;

                    $uri = route('email-verify');
                    if ($userData->email_verified == 1 && $userData->firstsiginup == 1) {
                        $uri = route('rx-user-agreement');
                        $userData->firstsiginup = 0;
                    } elseif ($userData->email_verified == 1) {
                        $uri = route('account-home');

                    } else {
                        // $home = new HomeController();
                        // $home->resendEmailVerification();
                        $message = new MessageController();
                        $user = User::find(Auth::user()->id);
                        $response = $message->sendVerifyEmailOtp(Session::get('change-email')?Session::get('change-email'):$user->email, $user->id);
                        if (!$response) {
                            return Response::json(array(
                                'success' => true,
                                'message' => 'Something went wrong! Please try again later.',

                            ), 400);
                        }
                        UserLogs::create([
                            'type' => 'sentEmailOtp',
                            'description' => 'Email otp has been sent successfully',
                            'user_id' => Auth::user()->id
                        ]);
                        Session::flash('success', 'Email verification code has been sent to your registered email address. Please check your email.');

                    }
                    $userData->save();
                    return Response::json(array(
                        'success' => true,
                        'message' => 'Your Phone Number has been verified successfully.',
                        'uri' => $uri,

                    ), 200);
                }


            } else {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Incorrect verification code! Please try again.']

                ), 400);
            }
        }

    }

    public function getRegistrationDetailsView()
    {
        if (auth()->check() && auth()->user()->phone_verified == 1)
            return redirect()->route('account-home');
        elseif(!auth()->check())
            return redirect()->back();
        else
            return view('auth.register_details');
    }

    public function getRegistrationConfirmationView()
    {
        return view('auth.social-login-confirmation');
    }

    public function registerSecondStep(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'phone:US'],
            'full_number' => ['required', 'phone:US']
        ]);
        $checkPhone = User::where('phone', $request->full_number)->first();
        if ($checkPhone && $checkPhone->phone) {
            throw ValidationException::withMessages(['phone' => 'This phone number already exists. Please try with new phone number.']);
        }
        // send otp to this user phone
        $message = new MessageController();
        $response = $message->sendVerifyOtp($request->full_number, Auth::user()->id);
        if (!$response) {
            Session::flash('warning', 'Something went wrong! Please try again later.');
            return redirect()->route('otp-verify');
        }
        $user = User::find(Auth::user()->id);
        Session::put('change-phone', $request->full_number);
        // $user->update([
        //     'phone' => $request->full_number,
        //     'recieve_msg_agreement' => isset($request->msg_agreement)?$request->msg_agreement:0,
        // ]);
        Session::flash('success', 'Please enter the verification code sent to your phone number ending with xxxxxxxx' . substr($request->full_number, -2) . '.');
        return redirect()->route('otp-verify');
    }

    public function continueOtpVerification()
    {
        $user = User::where('id', Auth::user()->id)->where('phone_verified', 0)->first();

        if ($user->phone) {
            $message = new MessageController();
            $response = $message->sendVerifyOtp($user->phone, Auth::user()->id);
            if (!$response) {
                Session::flash('warning', 'Something went wrong! Please try again later.');
                return redirect()->route('otp-verify');
            }
            Session::flash('success', 'Please enter the verification code sent to your phone number ending with xxxxxxxx' . substr($user->phone, -2) . '.');
            return redirect()->route('otp-verify');
        } else {
            Session::flash('warning', 'Please verify your phone number to proceed further.');
            return redirect()->route('register-details');
        }
    }

    public function verifyOtp(Request $request)
    {
        $exists = User::where('id', Auth::user()->id)->where('phone_otp', $request->otp)->where('phone_verified', 0)->first();
        if ($exists) {
            $exists->update(['phone_verified' => 1]);
            Session::flash('success', 'Phone number has been verified successfully.');

            // check if live in state
            $isLocationLive = LocationCheckController::isLocationLive();
            if (!$isLocationLive) {
//                Session::flush();
//                Auth::logout();
                return redirect()->route('state-not-live');
            } else {
                User::where('id', auth()->user()->id)->update([
                    'user_state_allowed' => true
                ]);

                if (Auth::user()->email_verified == 0) {
                    return redirect()->route('email-verify');
                } else {
                    return redirect()->route('rx-user-agreement');
                }

            }
        } else {
            Session::flash('warning', 'Invalid verification code.');
            return back();
        }
    }

    public function resendOtp(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->phone_verified == 1) {
                return redirect()->route('account-home');
            }
        }
        $user = User::where('id', Auth::user()->id)->where('phone_verified', 0)->select('id', 'phone')->first();

        $resendCount = UserLogs::where('user_id', Auth::user()->id)
            ->where('type', 'resentOtp')
            ->whereDate('created_at', date('Y-m-d'))
            ->orderBy('created_at', 'DESC')->get();

        if ($resendCount->count() !== 0) {
            $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            if ($currentDate <= $resendDate && $resendCount->count() >= 3) {
                return back()->with('warning', 'Verification code request limit exceeded. Please try after 10 minutes.');
            }

        }
        if ($user || !empty(Session::get('change-phone'))) {
            $message = new MessageController();
            if (Session::get('change-phone')) {
                $response = $message->sendVerifyOtp(Session::get('change-phone'), $user->id);
                if (!$response) {
                   // Session::flash('warning', 'Something went wrong! Please try again later.');
                    return redirect()->route('otp-verify')->with(['error_title'=>Error_4001_Title,'error_description'=>Error_4001_Description]);
                }
            } else {
                $response =  $message->sendVerifyOtp($user->phone, $user->id);
                if (!$response) {
                    //Session::flash('warning', 'Something went wrong! Please try again later.');
                    return redirect()->route('otp-verify')->with(['error_title'=>Error_4001_Title,'error_description'=>Error_4001_Description]);
                }
            }
//            UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentOtp')->delete();
            UserLogs::create([
                'type' => 'resentOtp',
                'description' => 'OTP has been resent successfully',
                'user_id' => Auth::user()->id
            ]);
            return redirect()->route('otp-verify')->with('success', 'Verification code has been sent successfully.');
        } else {
            return back()->with('warning', 'Your phone number is already verified.');
        }
    }

    public function resendOtpAjax(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->where('phone_verified', 0)->select('id', 'phone')->first();
        $resendCount = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentOtp')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
        if ($resendCount->count() !== 0) {
            $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            if ($currentDate <= $resendDate && $resendCount->count() >= 3) {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Your phone number verification usage limit exceeded. Please try after 10 minutes.']

                ), 400);
            }
        }
        if ($user || !empty(Session::get('change-phone'))) {
            $message = new MessageController();
            if (Session::get('change-phone')) {

                $response = $message->sendVerifyOtp(Session::get('change-phone'), Auth::user()->id);
                if (!$response) {
                    return Response::json(array(
                        'success' => false,
                        'error_title' => Error_4001_Title,
                        'error_description' => Error_4001_Description,

                    ), 400);
                }
            } else {
                $response =  $message->sendVerifyOtp($user->phone, $user->id);
                if (!$response) {
                    return Response::json(array(
                        'success' => false,
                        'error_title' => Error_4001_Title,
                        'error_description' => Error_4001_Description,

                    ), 400);
                }

            }
            UserLogs::create([
                'type' => 'resentOtp',
                'description' => 'OTP has been resent successfully to your registered phone number.',
                'user_id' => Auth::user()->id
            ]);

            return Response::json(array(
                'success' => true,
                'message' => ['Verification code has been sent to your registered phone number.']

            ), 200);
        } else {
            return Response::json(array(
                'success' => false,
                'errors' => ['Your phone number is already verified.']

            ), 400);
        }
    }

    public function changeMobileNo(Request $request)
    {
        $request->validate([
            'full_number' => 'required|phone:US'
        ]);
        if (Auth::user()->phone !== $request->full_number) {
            $checkPhone = User::where('phone', $request->full_number)->first();
            if ($checkPhone && $checkPhone->phone) {
                throw ValidationException::withMessages(['phone' => 'This phone number already exists. Please try with a new phone number.']);
            }
        }
        if (Auth::user()->phone == $request->full_number) {
                throw ValidationException::withMessages(['phone' => 'You cannot update same phone number. Please enter a new phone number.']);
        }
        $resendCount = UserLogs::where('user_id', Auth::user()->id)->where('type', 'changeNumber')->orderBy('created_at', 'DESC')->get();

        if ($resendCount->count() !== 0) {
            if ($resendCount->count() >= 1) {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Phone number changing limit exceeded. Please try again after 10 minutes.']

                ), 400);

            }

        }
        $user = User::where('id', auth()->user()->id)->where('phone_verified', 0)->first();
        if ($user) {
            Session::put('change-phone', $request->full_number);
            $message = new MessageController();
            $response =  $message->sendVerifyOtp($request->full_number, $user->id);
            if (!$response) {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Something went wrong! Please try again later.']

                ), 400);
            }
            User::where('id', auth()->user()->id)->update(['phone' => $request->full_number]);
            UserLogs::create([
                'type' => 'changeNumber',
                'description' => 'Phone number has been updated successfully.',
                'user_id' => Auth::user()->id
            ]);
            $resend = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentOtp');
            $resend->delete();
            return Response::json(array(
                'success' => false,
                'message' => ['Verification code has been sent to your new phone number.']

            ), 200);
        } else {
            return Response::json(array(
                'success' => false,
                'errors' => ['Your phone number is already verified.']

            ), 400);
        }
    }

    public function reSentEmailVerify(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->phone_verified == 0) {
                return redirect()->route('otp-verify');
            }
            if (Auth::user()->email_verified == 1) {
                return redirect()->route('account-home');
            }
        }
        $resendCount = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentEmail')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
        if ($resendCount->count() !== 0) {
            $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            if ($currentDate <= $resendDate && $resendCount->count() >= 3) {
                return back()->with('warning', 'Email verification code request limit exceeded. Please try after 10 minutes.');
            }
        }
        // $home = new HomeController();
        // $home->resendEmailVerification();
        $message = new MessageController();
        $user = User::find(Auth::user()->id);
        $response = $message->sendVerifyEmailOtp(Session::get('change-email')?Session::get('change-email'):$user->email, $user->id);
        if (!$response) {
            return redirect()->route('email-verify')->with(['error_title'=>Error_4002_Title,'error_description'=>Error_4002_Description]);
        }
        UserLogs::create([
            'type' => 'resentEmail',
            'description' => 'Email has been resent successfully',
            'user_id' => Auth::user()->id
        ]);
        Session::flash('success', 'Email verification code has been sent successfully.');
        return redirect()->route('email-verify');
    }

    public function reSentEmailVerifyAjax(Request $request)
    {
        $resendCount = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentEmail')->whereDate('created_at', date('Y-m-d'))->orderBy('created_at', 'DESC')->get();
        if ($resendCount->count() !== 0) {
            $resendDate = strtotime(date('Y-m-d H:i:s', strtotime($resendCount->first()->created_at))) + (60 * 10);
            $currentDate = strtotime(date('Y-m-d H:i:s'));
            if ($currentDate <= $resendDate && $resendCount->count() >= 3)  {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Email verification usage limit exceeded. Please try after 10 minutes.']

                ), 400);
            }

        }
        // $home = new HomeController();
        // $home->resendEmailVerification('Ajax');
        $message = new MessageController();
        $user = User::find(Auth::user()->id);
        $response = $message->sendVerifyEmailOtp(Session::get('change-email')?Session::get('change-email'):$user->email, $user->id);
        if (!$response) {
            return Response::json(array(
                'success' => false,
                'error_title' => Error_4002_Title,
                'error_description' => Error_4002_Description,

            ), 400);
        }
        UserLogs::create([
            'type' => 'resentEmail',
            'description' => 'Email has been resent successfully',
            'user_id' => Auth::user()->id
        ]);
        return Response::json(array(
            'success' => true,
            'message' => ['Email verification code has been sent successfully.']

        ), 200);

    }

    public function changeEmail(Request $request)
    {

         $request->validate( [
            'new_email' => 'required|email'
        ]);
        if (Auth::user()->email !== $request->new_email) {
            $checkPhone = User::where('email', $request->new_email)->first();
            if ($checkPhone && $checkPhone->email) {
                throw ValidationException::withMessages(['new_email' => 'This email address already exists. Please try with a new email address.']);
            }
        }
        if (Auth::user()->email == $request->new_email) {
                throw ValidationException::withMessages(['new_email' => 'You cannot update the same email address. Please enter a new email address.']);
        }

        $resendCount = UserLogs::where('user_id', Auth::user()->id)->where('type', 'changeEmail')->orderBy('created_at', 'DESC')->get();
        if ($resendCount->count() !== 0) {
            if ($resendCount->count() >= 1) {
                return Response::json(array(
                    'success' => false,
                    'errors' => ['Email changing limit exceeded. Please try after 10 minutes.']

                ), 400);

            }

        }
        $user = User::where('id', auth()->user()->id)->where('email_verified', 0)->first();
        if ($user) {

            // $home = new HomeController();
            // $home->resendEmailVerification();
            $message = new MessageController();
            $user = User::find(Auth::user()->id);
            $response = $message->sendVerifyEmailOtp(Session::get('change-email')?Session::get('change-email'):$request->new_email, $user->id);
            if (!$response) {
                return Response::json(array(
                    'success' => true,
                    'message' => 'Something went wrong! Please try again later.',

                ), 400);
            }
            User::where('id', auth()->user()->id)->update(['email' => $request->new_email]);
            UserLogs::create([
                'type' => 'changeEmail',
                'description' => 'Email has been updated successfully.',
                'user_id' => Auth::user()->id
            ]);
            $resend = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentEmail');
            $resend->delete();
            return back()->with('success', 'Email has been updated successfully.');
        } else {
            return Response::json(array(
                'success' => false,
                'errors' => ['Your email is already verified.']

            ), 400);


        }
    }

    public function rxUserAgreement()
    {
        if (auth()->user()->rx_agreement == 1) {
            return redirect()->route('account-home');
        }
        $ip_location = auth()->user()->ip_location;
        return view('user.onboard.rx-agreement', compact('ip_location'));
    }

    public function submitRxUserAgreement(Request $request)
    {
        User::where('id', Auth::user()->id)->update(['rx_agreement' => 1]);
        if (Session::has("selected_plan_id")) {
            return redirect()->route('medical-questions');
        } else {
            return redirect()->to(env('WP_URL'));
        }
    }

    public function getQuestionnaire()
    {
        if (Session::has("selected_plan_id")) {
            return view('user.onboard.medical-questions');
        } else {
            return redirect()->to(env('WP_URL'));
        }
    }

    public function submitAnswers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), (new QuesAnsRequest())->rules());

            if ($validator->fails()) {
                return back()->with('mismatchError', 'Questions/Answers were mismatched with predefined values.');
            }

            if ($request['ans_10__1'] !== 'Yes') { // For blood pressure handling
                return back()->with('mismatchError', 'Something went wrong!');
            }

            Session::put("questionare_data", json_encode($request->all()));

            // store DOB & gender for record
            User::where('id', Auth::user()->id)->update([
                'gender' => $request['ans_1'],
                'dob' => $request['ans_2']
            ]);

            return redirect()->route('upload-documents');
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public function orderSummary()
    {
        if (!session()->has('selected_plan_id')) return redirect()->to(env('WP_URL'));
        if (!session()->has('questionare_data')) return redirect()->route('medical-questions');

        if (Session::has('selected_plan_id') && Session::get('selected_plan_qty')) {
            $selected_plan_id = Session::get('selected_plan_id');
            $selected_plan_qty = Session::get('selected_plan_qty');
            $plan_details = Controller::PlanDetails($selected_plan_id,$selected_plan_qty);
            if($plan_details['status']==1){
                $plan = $plan_details['info'];

                $orderSummary = (object) [
                    'shipping_cost' => $plan->plan_details->shipping_cost,
                    'total_price' => $plan->plan_details->price + $plan->plan_details->shipping_cost
                ];
                
                $sdCommission = 0;
                $error = 0;
                // if(env('APP_ENV')=='local' || env('APP_ENV')=='staging'){
                //     $sdCommission = 30;
                // }  else {
                //     $sdAPI = new SmartDoctorsApiController();
                //     $response = json_decode($sdAPI->getSmartDoctorsCommission(), true);
                //     if (!$response) {
                //         $error = ['error_title'=>Error_1001_Title,'error_description'=>Error_1001_Description];
                //     } else {
                //         $sdCommission = $response['smartdoctor_commission'];
                //     }
                // }
                $setting = GeneralSetting::first();
                $sdCommission = $setting->consultation_fee;
                
                return view('user.onboard.order-summary', compact('plan', 'orderSummary', 'sdCommission','error'));
            } else {
                return redirect()->to(env('WP_URL'));
            }

        } else {
            return redirect()->to(env('WP_URL'));
        }
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart');
        if (!empty($cart)) {
            session()->forget('cart');
        }
        
        // if(env('APP_ENV')!='local' && env('APP_ENV')!='staging'){
        //     $sdAPI = new SmartDoctorsApiController();
        //     $response = json_decode($sdAPI->getSmartDoctorsCommission(), true);
        //     if (!$response) {
        //         return redirect()->back();
        //     }
        // }   
        
        $selected_plan_id = Session::get('selected_plan_id');
        $selected_plan_qty = Session::get('selected_plan_qty');
        $plan_details = Controller::PlanDetails($selected_plan_id,$selected_plan_qty);
        if($plan_details['status']==1){
            $plan = $plan_details['info'];
        } else {
            return redirect()->to(env('APP_ENV'));
        }

        // $totalPrice = $plan->plan_details->total + $this->shippingCost;
        $totalPrice = $plan->plan_details->total;
        $isPromoActive = false;
        // echo session()->has('promoSummary');exit;
        $promoDiscountPercentage = 0;
        $promoDiscountValue = 0;
        if (session()->has('promoSummary')) {
            $promoSummary = session()->get('promoSummary');

            $promoCode = $promoSummary['promoCode'];
            $promoDiscountPercentage = $promoSummary['discountPercentage'];
            $promoDiscountValue = $promoSummary['discountValue'];
            $productPriceAfterDiscount = $promoSummary['productPriceAfterDiscount'];
            $totalPrice = $promoSummary['productPriceAfterDiscount'];
            $isPromoActive = true;
        }

        $cart = array(
            "product_image" => $plan->plan_image,
            "product_name" => $plan->product->name,
            "plan_name" => $plan->plan_type->name,
            "medicine_variant_name" => $plan->medicine_variant->name,
            "plan_title" => $plan->plan_title,
            "product_price" => $plan->plan_details->price,
            "shipping_cost" => $plan->plan_details->shipping_cost,
            "plan_discount" => $plan->plan_details->discount,
            "product_total_price" => $plan->plan_details->total,
            "promo_code" => @$promoCode ?? null,
            "promo_discount_percent" => $promoDiscountPercentage,
            "promo_discount" => $promoDiscountValue,
            "total_price" => $totalPrice,
            "strength" => $plan->strength,
            "product_ndc" => @$plan->product_ndc,
            "product_ndc_2" => @$plan->product_ndc_2,
            "product_quantity" => $selected_plan_qty,
            "is_promo_active" => $isPromoActive,
        );
        session()->put('cart', $cart);
        return redirect()->route('shipping-info');
    }

    public function shippingInfo()
    {
        if (!session()->has('selected_plan_id')) return redirect()->to(env('WP_URL'));
        if (!session()->has('questionare_data')) return redirect()->route('medical-questions');

        return view('user.onboard.shipping-info');
    }

    public function addNewAddress(Request $request)
    {
        $request->validate([
            'address_line' => 'required|max:50|regex:/^[a-zA-Z0-9",.\/\s,\'-]*$/',
            'city' => 'required|regex:/^[A-Za-z ]+$/',
            'zipcode' => 'required|postal_code:US',
            'state' => 'required'
        ],['address_line.required'=>'The street 1 field is required.']);
        UserAddress::create([
            'user_id' => Auth::user()->id,
            'name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'address_line' => $request->address_line,
            'address_line2' => $request->address_line2,
            'city' => $request->city,
            'zipcode' => $request->zipcode,
            'state' => $request->state,
            'phone' => Auth::user()->phone
        ]);
        Session::flash('success', 'New shipping address added successfully.');
        return back();
    }

    public function deleteAddress(Request $request)
    {
        UserAddress::where('id', $request->address_id)->delete();
        return back();
    }

    public function editAddress(Request $request)
    {
        $request->validate([
            'edit_address_line' => 'required|max:50|regex:/^[a-zA-Z0-9",.\/\s,\'-]*$/',
            'edit_address_line2' => 'max:50|regex:/^[a-zA-Z0-9",.\/\s,\'-]*$/',
            'edit_city' => 'required|regex:/^[A-Za-z ]+$/',
            'edit_zipcode' => 'required|postal_code:US',
            'edit_state' => 'required'
        ],['edit_address_line.required'=>'The street 1 field is required.','edit_city.required'=>'The city field is required.','edit_zipcode.required'=>'The zipcode field is required.','edit_state.required'=>'The state field is required.']);
        UserAddress::where('id', $request->edit_address_id)
            ->update([
                'address_line' => $request->edit_address_line,
                'address_line2' => $request->edit_address_line2,
                'city' => $request->edit_city,
                'zipcode' => $request->edit_zipcode,
                'state' => $request->edit_state
            ]);
        return back();
    }

    public function completeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required'
        ]);

        $user = auth()->user();
        $user_id = $user->id;
        if (empty($user->phone)) {
            return redirect()->route('account-home')->with(['error_title'=>Error_9001_Title,'error_description'=>Error_9001_Description]);
        }
        $setting = GeneralSetting::first();

        $question_answer = QuestionAnswer::create([
            'user_id' => $user_id,
            'answers' => session()->get("questionare_data")
        ]);

        $cart = session()->get('cart');
        $plan_amount = 0;
        $isSubscription = false;

        if (Session::has('selected_plan_id') && Session::has('selected_plan_qty')) {
            $selected_plan_id = Session::get('selected_plan_id');
            $selected_plan_qty = Session::get('selected_plan_qty');
            $plan_details = Controller::PlanDetails(Session::get('selected_plan_id'),Session::get('selected_plan_qty'));
            if($plan_details['status']==1){
                $plan = $plan_details['info'];
                $plan_amount = $plan->plan_details->total;
            } else {
                return redirect()->to(env('WP_URL'));
            }
        } else {
            return redirect()->to(env('WP_URL'));
        }

        $address = UserAddress::find($request->shipping_address);
        // $total_price = $plan_amount + $this->shippingCost;
        $total_price = $plan_amount;
        $order_no = time();

        $active = 0;

        if (session('renewPlan')) {
            $this->disableActiveOrder();
        }
        if (Session::get('change-plan') == 1) {
            $active = 0;
        } else {
            $this->disableActiveOrder();
        }

        $this->cancelUnpaidOrders();

        // $sdAPI = new SmartDoctorsApiController();
        // $response = json_decode($sdAPI->getSmartDoctorsCommission(), true);
        // $sdCommission = 0;
        // if(env('APP_ENV')=='local' || env('APP_ENV')=='staging'){
        //     $sdCommission = 30;
        // }  else {
        //     if (!$response) {
        //         return redirect()->back()->with(['error_title'=>Error_1001_Title,'error_description'=>Error_1001_Description]);
        //     } else {
        //         $sdCommission = $response['smartdoctor_commission'];
        //     }
        // }
        $is_subscription = 0;
        if($plan->plan_type->subscription_type==1){
            $is_subscription = 1;
        }

        $order = Order::create([
            'order_no' => $order_no,
            'user_id' => $user_id,
            'is_active' => $active,
            'is_subscription' => $is_subscription,
            'question_ans_id' => $question_answer->id,
            'plan_id' => Session::get('selected_plan_id'),
            'product_image' => $cart['product_image'],
            'product_name' => $cart['product_name'],
            'plan_name' => $cart['plan_name'],
            'plan_title' => $cart['plan_title'],
            'medicine_variant' => $cart['medicine_variant_name'],
            'strength' => $cart['strength'],
            'product_price' => $cart['product_price'],
            'shipping_cost' => $cart['shipping_cost'],
            'plan_discount' => $cart['plan_discount'],
            'product_total_price' => $cart['product_total_price'],
            'is_promo_active' => $cart['is_promo_active'],
            'promo_code' => $cart['promo_code'],
            'promo_discount_percent' => $cart['promo_discount_percent'],
            'promo_discount' => $cart['promo_discount'],
            'total_price' => $cart['total_price'],
            'product_ndc' => $cart['product_ndc'],
            'product_ndc_2' => $cart['product_ndc_2'],
            'product_quantity' => $cart['product_quantity'],
            'telemedicine_consult' => $setting->consultation_fee,
            'shipping_fullname' => $user->first_name . ' ' . $user->last_name,
            'shipping_address_line' => $address->address_line,
            'shipping_address_line2' => $address->address_line2,
            'shipping_city' => $address->city,
            'shipping_zipcode' => $address->zipcode,
            'shipping_state' => $address->state,
            'shipping_phone' => $user->phone
        ]);

        if ($plan->plan_type->subscription_type==1) {
            // $order->update([
            //     'is_subscription' => true
            // ]);

            OrderSubscription::create([
                'order_no' => $order->order_no,
                'start_date' => now()->toDateTimeString(),
            ]);
        }

        BelugaOrderDetail::create(['order_no'=>$order->order_no]);

        session()->put('order_id', $order->id);

        if (session('refillRequest')) session()->forget('refillRequest');
        if (session('renewPlan')) session()->forget('renewPlan');

        return redirect()->route('make-payment');
    }

    private function disableActiveOrder()
    {
        $activeOrder = Order::where('user_id', auth()->user()->id)
            ->where('status', 'Prescribed')
            ->where('is_active', true)
            ->first();
        if ($activeOrder) {
            $activeOrder->update(['is_active' => false]);
        }
    }

    private function cancelUnpaidOrders()
    {
        $unpaidOrders = Order::where('user_id', auth()->user()->id)
            ->where('payment_status', 'Unpaid')
            ->where('status', 'Pending')
            ->get();

        foreach ($unpaidOrders as $order) {
            $order->update([
                'status' => 'Cancelled',
                'cancel_reason' => 'Order has been cancelled due to payment not received from the customer.'
            ]);
        }
    }

}
