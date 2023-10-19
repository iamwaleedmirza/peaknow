<?php

namespace App\Http\Controllers;

use App\Enums\Log\PaymentLogEvent;
use App\Enums\Log\Status;
use App\Http\Controllers\Email\EmailController;
use App\Models\ContactUs;
use App\Models\GeneralSetting;
use App\Models\Logs\AuthorizeCustomerLogs;
use App\Models\Order;
use App\Models\Plans;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use TimeHunter\LaravelGoogleReCaptchaV3\Facades\GoogleReCaptchaV3;

class StaticPageController extends Controller
{

    public function getAboutUsView()
    {
        return view('static-pages.about-us');
    }

    public function getFaqView()
    {
        return view('static-pages.faq');
    }

    public function getEmailView()
    {
        return view('emails.verify_email');
    }

    public function getBlogsView()
    {
        return view('static-pages.blogs');
    }

    public function contactUs()
    {
        return view('static-pages.contact-us');
    }

    public function submitContactUs(Request $request)
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
        $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|phone:US',
            'full_number' => 'required|phone:US',
            'state' => 'required',
            'message' => 'required'

        ]);
        ContactUs::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->full_number,
            'state' => $request->state,
            'message' => $request->message
        ]);
        $data = array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->full_number,
            'state' => $request->state,
            'message' => $request->message
        );
        $emailsend = new EmailController();
        $emailsend->sendContactUsMail($data);
        return redirect()->back()
            ->with('success', 'Thank you for reaching us. We will contact you soon.');
    }

    public function getOtpVerifyView()
    {
        if (auth()->user()->phone == null && session::get('change-phone') == null && auth()->user()->phone_verified == 0) {
            return redirect()->route('register-details');
        } elseif (auth()->user()->phone_verified == 0) {
            if (session::get('otp-send') == 1) {
                return redirect()->route('user.register_successful');
            }
            return view('auth.otp-verify');
        } else {
            return redirect()->route('account-home');
        }
    }

    public function getemailVerifyView()
    {
        if (auth()->user()->phone_verified == 0) {

            return redirect()->route('otp-verify');
        }

        if (auth()->user()->email_verified == 0) return view('auth.email-verify');

        else return redirect()->route('account-home');
    }

    public function getDocumentUploadView()
    {
        $documents = [];

        $homeController = new HomeController();

        $documents['govt_id'] = $homeController->getUserDocumentPath('govt_id');
        $documents['selfie'] = $homeController->getUserDocumentPath('selfie');

        return view('auth.documents-upload', compact('documents'));
    }

    public function getPasswordResetView()
    {
        return view('auth.passwords.email');
    }

    public function getAdminLoginView()
    {
        //Added Check for admin if he is logged in or not so if he is then redirect him to admin home

        if (Auth::check() && Auth::user()->u_type != 'patient') {
            return redirect()->route('admin.home');
        } else if (Auth::check() && Auth::user()->u_type == 'patient') {
            return redirect()->route('account-home');
        }
        return view('admin.login');
    }

    public function homeEmailSubscription(Request $request)
    {
        if (app()->environment(['production', 'staging'])) {
            if (GoogleReCaptchaV3::verifyResponse(
                    $request->input('g-recaptcha-response')
                )->isSuccess() == false
            ) {
                throw ValidationException::withMessages([
                    'email' => 'Google reCaptcha Didnt Verified!!'
                ]);
            }
        }
        request()->validate([
            'email' => 'required|email|unique:subscriptions',
            'agreement' => 'required',
        ]);
        $user_id = (Auth::user()) ? Auth::user()->id : 0;
        Subscription::create([
            'user_id' => $user_id,
            'email' => $request->email,
            'agreement' => $request->agreement
        ]);
        $user_data = ['email' => $request->email];
        $emailsend = new EmailController();
        $respo = $emailsend->sendsubscribedPopUpMail($user_data);
        Session::flash('success', __('Thank you for subscribing to Peaks Curative.'));
        \Cookie::queue('home-popup', 'done', 2147483647); // for 20 years
        return true;
    }

    public function saveCookie()
    {
        $intervalInMinutes = 1440; // 24 hrs
        \Cookie::queue(\Cookie::make('home-popup', 'done', $intervalInMinutes));
    }

    public function saveCookieAgreed()
    {
        \Cookie::queue('cookie_agreed', 'yes', 2147483647); // for 20 years
    }

    public function stateNotLive()
    {
        $strAllowedStates = '';

        $allowedStates = GeneralSetting::first()->allowed_states;

        $allowedStates = explode(',', $allowedStates);

        foreach ($allowedStates as $key => $state) {
            if ($key == 0) {
                $strAllowedStates = $strAllowedStates . trim($state);
            } else {
                $strAllowedStates = $strAllowedStates . ', ' . trim($state);
            }
        }

        return view('auth.state-not-live', compact('strAllowedStates'));
    }

    public function continueWithState()
    {
        if (auth()->user()) {
            User::where('id', auth()->user()->id)->update(['user_state_allowed' => true]);

            return redirect()->route('account-home');
        }
    }

    public function termsConditions()
    {
        return view('static-pages.terms-and-conditions');
    }

    public function telehealthConsent()
    {
        return view('static-pages.telehealth-consent');
    }

    public function privacyPolicy()
    {
        return view('static-pages.privacy-policy');
    }

    public function refundPolicy()
    {
        return view('static-pages.refund-policy');
    }

    public function cookiePolicy()
    {
        return view('static-pages.cookie-policy');
    }

    public function getPlansExceptCurrent()
    {
        $order = Order::where('user_id', Auth::user()->id)->where('is_active', 1)->where('is_changed', 0)->orderBy('created_at', 'DESC')->first();
        if (!$order) {
            abort(401);
        }
        $url = env('WP_URL').'/plans/?plan_id='.$order->plan_id.'&qty='.base64url_encode($order->product_quantity);
        return redirect()->to($url);

        // return view('user.onboard.plans', compact('plans'));
    }

    public function storageRoute(Request $request, $folder, $filename)
    {
        if ($request->hasValidSignature(false)) {
            return false;
        }
        $path = $folder . '/' . $filename;
        if (Storage::disk('local')->exists($path)) {
            try {
                return Storage::disk('local')->get($path);

            } catch (FileNotFoundException $e) {
                Log::debug($e->getMessage());
            }
        }
        return false;
    }

    public function logPaymentUpdated(Request $request)
    {
        try {
            AuthorizeCustomerLogs::create([
                'user_id' => auth()->user()->id,
                'event_type' => PaymentLogEvent::CHANGE_PAYMENT_METHOD,
                'status' => Status::SUCCESS,
//                'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
//                'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
