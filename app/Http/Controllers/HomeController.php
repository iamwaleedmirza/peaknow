<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Plans;
use App\Models\UserLogs;
use App\Models\UserAddress;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Utils\EmailController;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Utils\FileUploadController;
use App\Http\Controllers\Utils\OrderUtilsController;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function accountHome()
    {
        if (session()->has('selected_plan_id')) session()->forget('selected_plan_id');
        if (session()->has('questionare_data')) session()->forget('questionare_data');
        if (session()->has('cart')) session()->forget('cart');
        if (session()->has('order_id')) session()->forget('order_id');
        // if (session()->has('invoice_order_id')) session()->forget('invoice_order_id');
        if (session('refillRequest')) session()->forget('refillRequest');
        if (session()->has('promoSummary')) session()->forget('promoSummary');

        $pendingOrder = DB::table('orders as o')
            ->where('o.user_id', auth()->user()->id)
            ->where('is_active', false)
            ->where('o.payment_status', 'Unpaid')
            ->where('o.payment_page_success', false)
            ->where('o.status', 'pending')
            ->orderBy('o.created_at', 'desc')
            ->select(['o.id', 'o.order_no', 'o.user_id', 'o.is_subscription', 'o.plan_id', 'o.total_price', 'o.payment_status'])
            ->first();

        $orderUtils = new OrderUtilsController();
        $activeOrder = $orderUtils->getActiveOrder();
        $is_order_exist = 0;
        if($activeOrder){
            $plan = Controller::PlanDetails($activeOrder->plan_id,$activeOrder->product_quantity);
            $is_order_exist = $plan['status'];
        }

        return view('user.dashboard.account-home', compact('pendingOrder', 'activeOrder','is_order_exist'));
    }

    public function renewPlan($planId,$qty)
    {
        $plan_details = Controller::PlanDetails($planId,$qty);
        if($plan_details['status']==1){
            session()->put('selected_plan_id', $planId);
            session()->put('selected_plan_qty', $qty);
            if (auth()->user()) {
                session()->put('renewPlan', true);
                return redirect()->route('medical-questions');
            }
            return redirect()->route('login.user');
        } else {
            return redirect('/user/account/home')->with('renewError', 'Your current plan does not exist. Please choose another plan!');
        }
        // if (auth()->user()) {
        //     session()->put('renewPlan', true);
        //     return redirect()->route('medical-questions');
        // }

        // return redirect()->route('login.user');
    }

    public function verifyAccountForOrder()
    {
        $user = Auth::user();
        if ($user->phone_verified == 0) {
            return Response::json(array(
                'success' => false,
                'errors' => ['You cannot create an order without verifying phone number.']

            ), 400);

        }
        if ($user->email_verified == 0) {
            return Response::json(array(
                'success' => false,
                'errors' => ['You cannot create an order without verifying email address.']

            ), 400);

        } else {
            return Response::json(array(
                'success' => true,
                'message' => ['User is Verified']

            ), 200);
        }
    }

    public function accountInfo()
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user['selfie'] = $this->getUserDocumentPath('selfie');
        $user['govt_id'] = $this->getUserDocumentPath('govt_id');
        $order = Order::where('user_id',Auth::user()->id);
        $orderExist = false;
        $orderCount = $order->get()->count();
        if ( $orderCount !== 0) {
            $orderExist = true;
        }
        $isPhoneEnabled = false;
        $pendingOrder = Order::where('user_id',Auth::user()->id)->where('status','Pending');
        if ($pendingOrder->count() !== 0) {
            $isPhoneEnabled = false;
        }else{
            $isPhoneEnabled = true;
        }
        if ($user->liberty_patient_id && $order->where('is_active',true)->where('status','Prescribed')->first() !== null) {
            $isPhoneEnabled = true;
        }
        if (empty($user->liberty_patient_id) && $order->where('is_active',true)->where('status','Prescribed')->first() !== null) {
            $isPhoneEnabled = false;
        }
        if ( $orderCount == 0) {
            $isPhoneEnabled = true;
        }
        return view('user.dashboard.account-info', compact('user','orderExist','isPhoneEnabled'));
    }

    public function getUserDocumentPath($documentType)
    {
        $selfie = UserDocument::where('user_id', Auth::user()->id)
            ->where('document_type', $documentType)
            ->select(['document_path'])
            ->first();
        return empty($selfie['document_path']) ? "" : $selfie['document_path'];
    }

    public function resendEmailVerification($type = null)
    {
        $email_otp = rand(100001, 999999);
        $email = new EmailController();
        $user = User::find(Auth::user()->id);
        $user->email_otp = $email_otp;
        $user->save();
        if (Session::get('change-email')) {
            $user_data = array(
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'email' => Session::get('change-email'),
                'email_otp' => $email_otp,
            );

        } else {
            $user_data = array(
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'email_otp' => $email_otp,
            );

        }

        $emailData = [
            'account_username' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'year' => now()->format('Y'),
            'email_otp' => $email_otp
        ];
        $mailResponse = SendGridController::sendMail(auth()->user()->email, TEMPLATE_ID_EMAIL_OTP, $emailData, 'account-update@peakscurative.com');

//        $email->SendVerifyEmail($user_data, "Verify your email");

        if ($type == null) {
            return redirect()->back()
                ->with('success', 'Email verification code has been sent successfully.');
        }

    }

    public function updateInfo(Request $request)
    {

        $user = Auth::user();
        $order = Order::where('user_id',Auth::user()->id);
        $orderCount = $order->get()->count();
        $isPhoneEnabled = false;
        $pendingOrder = Order::where('user_id',Auth::user()->id)->where('status','Pending');
        if ($pendingOrder->count() !== 0) {
            $isPhoneEnabled = false;
        }else{
            $isPhoneEnabled = true;
        }
        if ($user->liberty_patient_id && $order->where('is_active',true)->where('status','Prescribed')->first() !== null) {
            $isPhoneEnabled = true;
            Session::put('change-liberty-phone', 1);
        }
        if (empty($user->liberty_patient_id) && $order->where('is_active',true)->where('status','Prescribed')->first() !== null) {
            $isPhoneEnabled = false;
        }
        if ($orderCount !== 0) {
           if ($isPhoneEnabled == true) {
                $request->validate([
                    'phone' => ['required', 'phone:US'],
                    'full_number' => ['required', 'phone:US'],
                    'email' => 'required|email|unique:users,email,' . Auth::user()->id,
                    'gender' => ['required', 'string']
                ]);
            }else{
                $request->validate([
                    'email' => 'required|email|unique:users,email,' . Auth::user()->id,
                    'gender' => ['required', 'string']
                ]);
            }

            $user_data = [
                'gender' => $request->gender
            ];
        }else{
            $request->validate([
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'dob' => ['required', 'string'],
                'phone' => ['required', 'phone:US'],
                'full_number' => ['required', 'phone:US'],
                'email' => 'required|email|unique:users,email,' . Auth::user()->id,
                'gender' => ['required', 'string']
            ]);
            $user_data = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'dob' => $request->dob,
                'gender' => $request->gender
            ];
            $checkPhone = User::where('phone',$request->full_number)->first();
            $isPhoneEnabled = true;
        }

        $response = ['success' => 'Your profile has been updated.', 'data' => null];

        if ($request->email !== Auth::user()->email) {
            Session::put('change-email', $request->email);
            $response = array_merge($response, ['data' => 'email_changed']);
            User::find(Auth::user()->id)->update($user_data);
            $resend = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentEmail');
            $resend->delete();


            return Response::json($response);
            $user_data = array_merge($user_data, ['email_verified' => 0]);

            UserLogs::create([
                'type' => 'changeEmailAtProfile',
                'description' => 'Email address changing limit exceeded. Please try after 30 minutes.',
                'user_id' => Auth::user()->id
            ]);

        } else {
            $response = array_merge($response, ['data' => 'account_updated']);
        }
        if ($request->full_number !== Auth::user()->phone && $isPhoneEnabled == true) {
            $checkPhone = User::where('phone',$request->full_number)->first();
            if ($checkPhone && $checkPhone->phone) {
                throw ValidationException::withMessages(['phone'=>'This phone number already exists. Please try with new phone number.']);
            }
            Session::put('change-phone', $request->full_number);
            $response = array_merge($response, ['data' => 'phone_changed']);
            User::find(Auth::user()->id)->update($user_data);
            $resend = UserLogs::where('user_id', Auth::user()->id)->where('type', 'resentOtp');
            $resend->delete();
            return Response::json($response);
            $user_data = array_merge($user_data, ['phone_verified' => 0]);
            UserLogs::create([
                'type' => 'changePhoneAtProfile',
                'description' => 'Phone number changing limit exceeded. Please try after 30 minutes.',
                'user_id' => Auth::user()->id
            ]);

        } else {
            $response = array_merge($response, ['data' => 'account_updated']);
        }

        $user = User::where('id',Auth::user()->id);
        $user->update($user_data);

        return Response::json($response);
    }
    //Checking user pending order and has liberty patient id then update to liberty
    public function checkAndUpdateLibertyBio($user,$phone)
    {
        if ($user->liberty_patient_id) {
            $libertyAPI = new LibertyApiController();
            $response = $libertyAPI->updatePatientInfo($user, $phone,'',true);

            if ($response->ok()) {
                $libertyPatientDetails = $response->json();
                if (!empty($libertyPatientDetails)) {
                    $user->update([
                        'phone' => $phone,
                    ]);
                    $user->libertyDetails()->update([
                        'PatientId' => $libertyPatientDetails['Id'],
                        'ExternalId' => $libertyPatientDetails['ExternalId'],
                        'AccountNumber' => $libertyPatientDetails['AccountNumber'],
                        'ChargeCode' => $libertyPatientDetails['ChargeCode'],
                        'FirstName' => $libertyPatientDetails['Name']['FirstName'],
                        'MiddleInitial' => $libertyPatientDetails['Name']['MiddleInitial'],
                        'LastName' => $libertyPatientDetails['Name']['LastName'],
                        'BirthDate' => Carbon::parse($libertyPatientDetails['BirthDate'])->format('Y-m-d'),
                        'Street1' => $libertyPatientDetails['Address']['Street1'],
                        'Street2' => $libertyPatientDetails['Address']['Street2'],
                        'City' => $libertyPatientDetails['Address']['City'],
                        'State' => $libertyPatientDetails['Address']['State'],
                        'Zip' => $libertyPatientDetails['Address']['Zip'],
                        'Gender' => $libertyPatientDetails['Gender'],
                        'SSN' => $libertyPatientDetails['SSN'],
                        'DriversLicenseNumber' => $libertyPatientDetails['DriversLicenseNumber'],
                        'Phone' => $libertyPatientDetails['Phone'],
                        'PhoneType' => $libertyPatientDetails['PhoneType'],
                        'Phone2' => $libertyPatientDetails['Phone2'],
                        'Phone2Type' => $libertyPatientDetails['Phone2Type'],
                        'Email' => $libertyPatientDetails['Email'],
                        'Language' => $libertyPatientDetails['Language'],
                        'CustomField1' => $libertyPatientDetails['CustomField1'],
                        'CustomField2' => $libertyPatientDetails['CustomField2'],
                        'CustomField3' => $libertyPatientDetails['CustomField3'],
                        'CustomField4' => $libertyPatientDetails['CustomField4'],
                        'Allergies' => $libertyPatientDetails['Allergies']?json_encode($libertyPatientDetails['Allergies'],true):[],
                    ]);
                }
                return $response->status();
            }else{
                return $response->body();
            }
        }else{
            $user->update([
                'phone' => $phone,
            ]);
            return 200;
        }
    }
    public function uploadDocuments(Request $request)
    {
        $response = [
            'status' => 'error',
            'message' => 'Upload failed.'
        ];

        if ($request->hasFile('govt_id')) {
            $response = $this->createUserDocument($request, 'govt_id');
        }

        if ($request->hasFile('selfie')) {
            $response = $this->createUserDocument($request, 'selfie');
        }

//        if (isset($request['verify_documents'])) {
//            return redirect()->route('order-summary');
//        }

        return response()->json($response);

//        return redirect()->back()->with('message', $response);
    }

    private function createUserDocument($request, $fieldName)
    {
        $validator = Validator::make($request->all(), [
            $fieldName => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ]);
        if ($validator->fails()) {
            return [
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ];
        }

        $fileUpload = new FileUploadController();
        $name = $fieldName . "_" . Auth::user()->id . "_" . uniqid() . "." . $request->file($fieldName)->extension();
        $input[$fieldName] = $fileUpload->upload($request, $name, $fieldName, 'user_' . $fieldName);

        $userDocument = UserDocument::where('user_id', Auth::user()->id)
            ->where('document_type', $fieldName)
            ->first();

        if ($userDocument) {
            $userDocument->document_path = $input[$fieldName];
            $userDocument->save();

        } else {
            UserDocument::create([
                'user_id' => Auth::user()->id,
                'document_type' => $fieldName,
                'document_path' => $input[$fieldName],
                'status' => 0
            ]);
        }

        if ($fieldName == 'govt_id') {
            $message = 'Govt ID uploaded successfully.';
        } else if ($fieldName == 'selfie') {
            $message = 'Selfie uploaded successfully.';
        } else {
            $message = "Image uploaded successfully.";
        }

        return [
            'status' => 'success',
            'message' => $message,
        ];
    }

    public function isDocumentsUploaded()
    {
        $selfie = auth()->user()->getDocumnetByType('selfie');
        $govtId = auth()->user()->getDocumnetByType('govt_id');
        $isSelfieExists = !empty($selfie);
        $isGovtIdExists = !empty($govtId);

        $redirectUrl = '';
        if ($isSelfieExists && $isGovtIdExists) {
            $redirectUrl = route('order-summary');
        }

        return response()->json([
            'is_selfie' => $isSelfieExists,
            'is_govt_id' => $isGovtIdExists,
            'url' => $redirectUrl,
        ]);
    }

    public function accountPaymentMethod()
    {
        return view('user.dashboard.account-payment');
    }

    public function accountAddresses()
    {
        if (auth()->user()->phone_verified == 0) {

            return redirect()->route('otp-verify');
        }

        if (auth()->user()->email_verified == 0) return redirect()->route('email-verify');

        $addresses = UserAddress::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->get();
        return view('user.dashboard.account-addresses', compact('addresses'));
    }
}
