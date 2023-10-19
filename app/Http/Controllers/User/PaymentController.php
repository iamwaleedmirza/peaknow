<?php

namespace App\Http\Controllers\User;

use App\Enums\Log\PaymentLogEvent;
use App\Enums\Log\Status;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Api\SmartDoctorsApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\Utils\AuthorizeController;
use App\Http\Controllers\Utils\FileUploadController;
use App\Models\GeneralSetting;
use App\Models\Logs\AuthorizeCustomerLogs;
use App\Models\Logs\PaymentLog;
use App\Models\Order;
use App\Models\OrderRefill;
use App\Models\OrderRefundHistory;
use App\Models\Plans;
use App\Models\User;
use App\Services\AuthorizeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use PDF;

class PaymentController extends Controller
{
    public $shippingCost = 0;

    public function __construct()
    {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
    }

    public function getPaymentPageToken()
    {
        if (!session()->has('pay_now')) {
            if (!session()->has('selected_plan_id')) return redirect()->to(env('WP_URL'));
            if (!session()->has('questionare_data')) return redirect()->route('medical-questions');
        }

        $order_id = session()->get('order_id');
        $order = Order::where('id', $order_id)->select(['total_price', 'product_price'])->first();
        if (!$order) {
            return redirect()->route('shipping-info');
        }

        $amount = $order->total_price;

        $host = url('/');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);

        // Set Hosted Form options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue("{\"text\": \"Pay\"}");

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentOrderOptions");
        $setting2->setSettingValue("{\"show\": false}");

        $setting3 = new AnetAPI\SettingType();
        $setting3->setSettingName("hostedPaymentReturnOptions");
        $setting3->setSettingValue("{\"showReceipt\" : false }");

        //        $settingBillingAddress = new AnetAPI\SettingType();
        //        $settingBillingAddress->setSettingName("hostedPaymentBillingAddressOptions");
        //        $settingBillingAddress->setSettingValue("{\"show\": false, \"required\": false}");

        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedPaymentIFrameCommunicatorUrl");
        $setting4->setSettingValue("{\"url\": \"$host/iframecommunicator\"}");

        //        $setting5 = new AnetAPI\SettingType();
        //        $setting5->setSettingName("hostedPaymentBillingAddressOptions");
        //        $setting5->setSettingValue("{\"required\": true}");

        $setting6 = new AnetAPI\SettingType();
        $setting6->setSettingName("hostedPaymentPaymentOptions");
        $setting6->setSettingValue('{"showBankAccount": false, "cardCodeRequired": true, "showCreditCard": true}');

        // $setting7 = new AnetAPI\SettingType();
        // $setting7->setSettingName("hostedPaymentBillingAddressOptions");
        // $setting7->setSettingValue('{"show": false, "required": false}');
        // Build transaction request
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);

        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);
        //        $request->addToHostedPaymentSettings($settingBillingAddress);
        $request->addToHostedPaymentSettings($setting3);
        $request->addToHostedPaymentSettings($setting4);
        //        $request->addToHostedPaymentSettings($setting5);
        $request->addToHostedPaymentSettings($setting6);
        // $request->addToHostedPaymentSettings($setting7);

        //execute request
        $controller = new AnetController\GetHostedPaymentPageController($request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $token = $response->getToken();

            return view('payment.make-payment', compact('token', 'order_id'));
        } else {
            return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
            echo "ERROR :  Failed to get hosted payment page token\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "RESPONSE : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        }
    }

    public function oneTimeOrderTransactionFailed(Request $request)
    {
        $order_id = session()->get('order_id');
        $order = Order::where('id', $order_id)->first();
        $emailManager = new EmailController();
        if (session('refillRequest')) {
            if (session('refillRequest')) session()->forget('refillRequest');
            $emailManager->sendRefillPaymentFailedMail($order);
        } else {
            $emailManager->sendOrderPaymentFailedMail($order);
        }
        return view('payment.payment-failed', compact('order'));
//        return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
    }

    public function makeUnPaidPayment($order)
    {
        $order = Order::where('order_no', $order)->first();
        session()->put('pay_now', true);
        if ($order->is_subscription == 1 && $order->status == 'Pending') {
            session()->put('order_id', $order->id);
            return redirect()->route('create.subscription');
        } elseif ($order->status == 'Pending') {
            session()->put('order_id', $order->id);
            return redirect()->route('make-payment');
        } else {
            Session::flash('error', 'Order has been cancelled.');
            return redirect()->back();
        }
    }

    public function getCustomerProfileToken()
    {
        if (session()->get('order_id')) {
            $order_id = session()->get('order_id');
        } else {
            return redirect()->route('account-home');
        }

        $host = url('/');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Set Hosted Form options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedProfileSaveButtonText");
        $setting1->setSettingValue("Save");
        //        $setting2 = new AnetAPI\SettingType();
        //        $setting2->setSettingName("hostedProfileReturnUrl");
        //        $setting2->setSettingValue("http://127.0.0.1:8000/");
        //        $setting3 = new AnetAPI\SettingType();
        //        $setting3->setSettingName("hostedProfileReturnUrlText");
        //        $setting3->setSettingValue("Complete Order");
        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedProfileIFrameCommunicatorUrl");
        $setting4->setSettingValue("$host/iframecommunicator");

        $setting5 = new AnetAPI\SettingType();
        $setting5->setSettingName("hostedProfileManageOptions");
        $setting5->setSettingValue("showPayment");

        $setting6 = new AnetAPI\SettingType();
        $setting6->setSettingName("hostedProfilePaymentOptions");
        $setting6->setSettingValue("showCreditCard");

        $setting7 = new AnetAPI\SettingType();
        $setting7->setSettingName("hostedProfileCardCodeRequired");
        $setting7->setSettingValue("true");

        $setting8 = new AnetAPI\SettingType();
        $setting8->setSettingName("hostedProfileHeadingBgColor");
        $setting8->setSettingValue("#2b2344");

        // $setting10 = new AnetAPI\SettingType();
        // $setting10->setSettingName("hostedProfileBillingAddressRequired");
        // $setting10->setSettingValue('false');

        // $setting9 = new AnetAPI\SettingType();
        // $setting9->setSettingName("hostedProfileBillingAddressOptions");
        // $setting9->setSettingValue('showNone');

         $setting11 = new AnetAPI\SettingType();
         $setting11->setSettingName("hostedProfileValidationMode");
         $setting11->setSettingValue('liveMode');

         $order = Order::where('id', $order_id)->first();

        // create customer profile if customer profile id not present
        if (empty(auth()->user()->customer_profile_id)) {
            $customerProfileResponse = (new AuthorizeService())->createCustomerProfile(auth()->user(), $order);

            if ($customerProfileResponse['success']) {
                $customer_profile_id = auth()->user()->customer_profile_id;
            } else {
                Log::error('error on get customer profile token', ['response' => $customerProfileResponse]);
                return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
            }

        } else {
            $customer_profile_id = auth()->user()->customer_profile_id;

            if (!empty(auth()->user()->payment_profile_id)) {
                $paymentProfileId = auth()->user()->payment_profile_id;
                $response = (new AuthorizeService())->makeTransaction($customer_profile_id, $paymentProfileId, $order, false);

                if ($response['success']) {
                    return redirect()->route('payment-success', [
                        'order_id' => $order->id,
                        'transaction_id' => $response['transaction_id'],
                    ]);
                }
            }
        }

        $request = new AnetAPI\GetHostedProfilePageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($customer_profile_id);
        $request->addToHostedProfileSettings($setting1);
        //        $request->addToHostedProfileSettings($setting2);
        //        $request->addToHostedProfileSettings($setting3);
        $request->addToHostedProfileSettings($setting4);
        $request->addToHostedProfileSettings($setting5);
        $request->addToHostedProfileSettings($setting6);
        $request->addToHostedProfileSettings($setting7);
        $request->addToHostedProfileSettings($setting8);
        // $request->addToHostedProfileSettings($setting10);
        // $request->addToHostedProfileSettings($setting9);
        // $request->addToHostedProfileSettings($setting11);

        //execute request
        $controller = new AnetController\GetHostedProfilePageController($request);
        if (config('services.authorize.mode') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $token = $response->getToken();
            return view('payment.manage_customer_profile', compact('token', 'order_id', 'customer_profile_id'));
        } else {
            Log::error('error getting customer page token', ['errors' => $response->getMessages(),]);
            return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
        }
    }

    public function createCustomerPaymentProfile($order_id, $customer_profile_id)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.merchant_login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.merchant_transaction_key'));

        $refId = 'ref' . time();

        // request requires customerProfileId
        $request = new AnetAPI\GetCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId($customer_profile_id);

        // Create the controller and get the response
        $controller = new AnetController\GetCustomerProfileController($request);

        if (config('services.authorize.mode') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            if ($response->getProfile()->getPaymentProfiles() == null) {
                Session::flash('error', 'Add payment method first to complete order.');
                return back();
            } else {
                $customerPaymentProfileId = $response->getProfile()->getPaymentProfiles()[0]->getCustomerPaymentProfileId();

                auth()->user()->update([
                    'payment_profile_id' => $customerPaymentProfileId,
                ]);

                $order = Order::where('user_id', auth()->user()->id)->where('id', $order_id)->first();

                if (empty($order)) {
                    return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
                }

                try {
                    AuthorizeCustomerLogs::create([
                        'user_id' => auth()->user()->id,
                        'event_type' => PaymentLogEvent::CREATE_CUSTOMER_PAYMENT_PROFILE,
                        'status' => Status::SUCCESS,
                        'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                        'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

                return $this->handlePaymentWithCustomerProfile($customer_profile_id, $customerPaymentProfileId, $order);
                // return redirect("/user/payment/success?order_id=" . $order_id . "&transaction_id=" . "&customer_profile_id=" . $customer_profile_id . "&payment_profile_id=" . $customer_paymentProfile_id);
            }
        } else {
            $order = Order::where('user_id', auth()->user()->id)->where('id', $order_id)->first();
            $emailManager = new EmailController();
            $emailManager->sendOrderPaymentFailedMail($order);

            try {
                AuthorizeCustomerLogs::create([
                    'user_id' => auth()->user()->id,
                    'event_type' => PaymentLogEvent::CREATE_CUSTOMER_PAYMENT_PROFILE,
                    'status' => Status::FAILURE,
                    'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                    'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

            return redirect()->route('account-home')->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
        }
    }

    public function handlePaymentWithCustomerProfile($customerProfileId, $paymentProfileId, $order, $isRefill = false)
    {
        $response = (new AuthorizeService())->makeTransaction($customerProfileId, $paymentProfileId, $order, $isRefill);

        if ($response['success']) {
            return redirect()->route('payment-success', [
                'order_id' => $order->id,
                'transaction_id' => $response['transaction_id'],
            ]);
        } else {
            return redirect()->route('payment.failed');
        }
    }

    public function getPaymentChangePageToken()
    {
        $host = url('/');
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Set Hosted Form options
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedProfileSaveButtonText");
        $setting1->setSettingValue("Save");

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedProfileValidationMode");
        $setting2->setSettingValue("liveMode");

        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedProfileIFrameCommunicatorUrl");
        $setting4->setSettingValue("$host/iframecommunicator");

        $setting5 = new AnetAPI\SettingType();
        $setting5->setSettingName("hostedProfileManageOptions");
        $setting5->setSettingValue("showPayment"); // hides shipping field

        $request = new AnetAPI\GetHostedProfilePageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setCustomerProfileId(auth()->user()->customer_profile_id);
        $request->addToHostedProfileSettings($setting1);
        $request->addToHostedProfileSettings($setting2);
        $request->addToHostedProfileSettings($setting4);
        $request->addToHostedProfileSettings($setting5);
        //execute request
        $controller = new AnetController\GetHostedProfilePageController($request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $token = $response->getToken();
            return view('user.dashboard.subscription_list.payment_change', compact('token'));
        } else {
            try {
                AuthorizeCustomerLogs::create([
                    'user_id' => auth()->user()->id,
                    'event_type' => PaymentLogEvent::CHANGE_PAYMENT_METHOD,
                    'status' => Status::FAILURE,
                    'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                    'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                ]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }

            return redirect()->back()->with(['error_title' => Error_2001_Title, 'error_description' => Error_2001_Description]);
        }
    }

    public function createSubscriptionFromCustomerProfile($intervalLength, $customerProfileId, $customerPaymentProfileId, $subscription_name, $subscription_unit, $amount, $occurrences = 6)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();
        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($subscription_name);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit($subscription_unit);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new \DateTime());
        $paymentSchedule->setTotalOccurrences($occurrences);
        //        $paymentSchedule->setTrialOccurrences("0");

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($amount);
        //        $subscription->setTrialAmount("0.00");

        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($customerProfileId);
        $profile->setCustomerPaymentProfileId($customerPaymentProfileId);
        //        $profile->setCustomerAddressId($customerAddressId);

        $subscription->setProfile($profile);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $subscription_id = $response->getSubscriptionId();
        } else {
            $subscription_id = null;
            $errorMessages = $response->getMessages()->getMessage();
            $error = $errorMessages[0]->getText();
        }
        return $subscription_id;
    }

    public function uploadInvoicePdf($order_id, $telemedConsult)
    {
        $order = (array)$this->getOrderDetails($order_id);
        $invoiceNo = rand(1000, 9999);
        $order['invoice_no'] = $order_id . '' . $invoiceNo;
        $order['telemedConsult'] = $telemedConsult;
        $order['shippingCost'] = $this->shippingCost;
        $order['discount'] = $this->shippingCost + $telemedConsult;
        $order['trans_date'] = null;
        view()->share('order', $order);
        $pdf = PDF::loadView('payment.invoice.invoice', $order);

        $document = new FileUploadController();

        if (!$order['invoice']) {
            $name = 'invoice_' . $order_id . '_' . uniqid() . '.' . 'pdf';
            $filePath = $document->uploadInvoice($order, $pdf, 'payment.invoice.invoice', $name, 'invoice');
            $order = Order::find($order['id']);
            $order->invoice = $filePath;
            $order->invoice_no = $order_id . '' . $invoiceNo;
            $order->save();
            $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
            $orderRefill->invoice = $filePath;
            $orderRefill->invoice_no = $order_id . '' . $invoiceNo;
            $orderRefill->save();
        }
        return $pdf;
    }

    public function getOrderDetails($orderId)
    {
        $order = DB::table('orders as o')
            ->where('o.id', $orderId)
            ->where('o.payment_status', 'Paid')
            ->join('users as u', 'o.user_id', '=', 'u.id')
            ->select(
                'o.*',
                'u.first_name',
                'u.last_name',
                'u.email'
            )
            ->first();

        return $order;
    }

    public function getSubscriptionOrderDetails($orderId)
    {
        $order = DB::table('orders as o')
            ->where('o.id', $orderId)
            ->where('o.payment_status', 'Paid')
            ->join('users as u', 'o.user_id', '=', 'u.id')
            ->select('o.*', 'u.first_name', 'u.last_name', 'u.email')
            ->first();

        return $order;
    }

    // public function sendNewOrderMailWithPromoForSubscription($order)
    // {
    //     $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;
    //     $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();

    //     $emailData = [
    //         'account_username' => $user->first_name . ' ' . $user->last_name,
    //         'year' => now()->format('Y'),
    //         'manage_order_action_url' => route('order-details', $order->order_no),
    //         'plan_name' => $order->plan_name.' ('.$order->plan_title.')',
    //         'plan_feature_2' => $order->product_name.' '.$order->medicine_variant.' ('.$order->strength.'MG)',
    //         'order_qty' => $order->product_quantity,
    //         'order_no' => $order->order_no,
    //         'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
    //         'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
    //         'plan_price' => $order->product_price,
    //         'tele_consult_cost' => $order->telemedConsult,
    //         'subtotal' => $order->product_total_price,
    //         'shipping_cost' => $order->shippingCost == 0 ? '<span style="color: #096036;">FREE</span>' : '$' . $order->shippingCost,
    //         'order_total' => $order->total_price
    //         'promo_code' => $order->promo_code,
    //     ];

    //     $emailData = [
    //         'account_username' => $user->first_name . ' ' . $user->last_name,
    //         'year' => now()->format('Y'),
    //         'manage_order_action_url' => route('order-details', $order->order_no),
    //         'plan_name' => $order->plan_name.' ('.$order->plan_title.')',
    //         'plan_feature_2' => $order->product_name.' '.$order->medicine_variant.' ('.$order->strength.'MG)',
    //         'order_qty' => $order->product_quantity,
    //         'order_no' => $order->order_no,
    //         'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
    //         'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
    //         'plan_price' => $order->product_price,
    //         'tele_consult_cost' => $order->telemedConsult,
    //         'shipping_cost' => $order->shippingCost == 0 ? '<span style="color: #096036;">FREE</span>' : '$' . $order->shippingCost,
    //         'order_total' => $order->total_price,
    //         'promo_code' => $order->promo_code,
    //         'discount_value' => $order->promo_discount,
    //         'new_plan_price_after_discount' => $order->total_price,
    //         'subtotal' => $order->total_price,
    //     ];
    //     $mailResponse = SendGridController::sendMail($order->email, TEMPLATE_ID_NEW_SUBSCRIPTION_ORDER_WITH_PROMO, $emailData, 'order-confirm@peakscurative.com');
    // }

    // public function sendNewOrderMailForSubscription($order)
    // {
    //     $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;
    //     $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();
    //     $emailData = [
    //         'account_username' => $user->first_name . ' ' . $user->last_name,
    //         'year' => now()->format('Y'),
    //         'manage_order_action_url' => route('order-details', $order->order_no),
    //         'plan_name' => $order->plan_name.' ('.$order->plan_title.')',
    //         'plan_feature_2' => $order->product_name.' '.$order->medicine_variant.' ('.$order->strength.'MG)',
    //         'order_qty' => $order->product_quantity,
    //         'order_no' => $order->order_no,
    //         'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
    //         'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
    //         'plan_price' => $order->product_price,
    //         'tele_consult_cost' => $order->telemedConsult,
    //         'subtotal' => $order->product_total_price,
    //         'shipping_cost' => $order->shippingCost == 0 ? '<span style="color: #096036;">FREE</span>' : '$' . $order->shippingCost,
    //         'order_total' => $order->total_price
    //     ];
    //     $mailResponse = SendGridController::sendMail($order->email, TEMPLATE_ID_NEW_ORDER, $emailData, 'order-confirm@peakscurative.com');
    // }

    public function sendNewOrderWithPromoMail($order_data)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();
        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;
        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'product_name' => $order->product_name,
            'medicine_variant' => $order->medicine_variant,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_no' => $order->order_no,
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'plan_price' => $order->product_price,
            'tele_consult_cost' => $order->telemedicine_consult,
            'shipping_cost' => $order->shipping_cost,
            'subtotal' => sprintf('%0.2f',$order->product_price + $order->telemedicine_consult + $order->shipping_cost),
            'sub_save_discount' => $order->plan_discount,
            'promo_code' => $order->promo_code,
            'promo_discount' => $order->promo_discount,
            'order_total' => $order->total_price,
            'invoice_url' => getImage($order->invoice),
            'year' => now()->format('Y'),
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_NEW_ORDER_WITH_PROMO, $emailData, 'order-confirm@peakscurative.com');
    }

    public function sendNewOrderMail($order_data)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();
        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;
        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'product_name' => $order->product_name,
            'medicine_variant' => $order->medicine_variant,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_no' => $order->order_no,
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'plan_price' => $order->product_price,
            'tele_consult_cost' => $order->telemedicine_consult,
            'shipping_cost' => $order->shipping_cost,
            'subtotal' => sprintf('%0.2f',$order->product_price + $order->telemedicine_consult + $order->shipping_cost),
            'sub_save_discount' => $order->plan_discount,
            'order_total' => $order->total_price,
            'invoice_url' => getImage($order->invoice),
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_NEW_ORDER, $emailData, 'order-confirm@peakscurative.com');
    }

    public function unsubscribePlan(Request $request)
    {
//        $result = $this->cancelSubscription($request->subscription_id);
//        if ($result == 'success') {
//            $subscription = SubscriptionPlan::where('user_id', Auth::user()->id)->where('subscription_id', $request->subscription_id)
//                ->where('active_status', 1);
//            $order = $subscription->first()->order;

//            $subscription->update([
//                'active_status' => 0
//            ]);
//            $order->update([
//                'is_active' => 0,
//                'telemedicine_consult' => 0,
//            ]);
//            $requestApi = new OrderController();
//            $response = $requestApi->cancelOrderOnSD($order->id);
//            if ($response) {
//                if ($order->status == 'Pending') {
//                    $this->cancelOrder($order, $request);
//                }
//                return back()->with('success', 'Subscription cancelled successfully.');
//            } else {
//                return back()->with('error', 'Something went wrong! Please try again later.');
//            }
//        }
        return back()->with('error', 'There is an error. Please try again.');
    }

    function cancelSubscription($subscriptionId)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = 'ref' . time();
        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $successMessages = $response->getMessages()->getMessage();
            $successMessages[0]->getText();
            $result = 'success';
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            $error = $errorMessages[0]->getText();
            $result = 'failed';
        }
        return $result;
    }

    private function cancelOrder($order, Request $request)
    {
        $refund = new FinanceController;
        $refund->refundOrderById($order->order_no, 0, 'Cancelled by user');

        if ($request->cancel_reason === 'others') {
            $cancelReason = $request->cancel_reason_other;
        } else {
            $cancelReason = $request->cancel_reason;
        }

        $order->update([
            'status' => 'Cancelled',
            'cancel_reason' => $cancelReason
        ]);
        $user = $order->user;
        $order->full_name = $user->first_name . ' ' . $user->last_name;
        //        Mail::to($user->email)->send(new OrderCancelledMail($order));
        $this->sendCancelOrderMail($order, $user);
    }

    public static function sendCancelOrderMail($order_data, $user)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();
        
        $orderRefund = OrderRefundHistory::where('order_no', $order->order_no)
            ->where('refill_number', $order->refill_count)
            ->first();

        if ($orderRefund) {
            $refundAmount = $orderRefund->amount;
        } else {
            $refundAmount = $order->total_price;
        }

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'cancelled_request_date' => Carbon::parse($order->updated_at)->format('F j, Y'),
            'order_no' => $order->order_no,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'total_refund_amount' => $refundAmount,
            'year' => now()->format('Y'),           
            
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_CANCELLED, $emailData, 'order-cancellation@peakscurative.com');
    }

}
