<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Api\SmartDoctorsApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\OrderRefillController;
use App\Http\Controllers\User\PaymentController;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderRefill;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use App\Http\Controllers\Beluga\BelugaApiController;

class PaymentSuccessController extends Controller
{
    public $shippingCost = 0;
    private $paymentController;
    private $invoiceController;

    public function __construct()
    {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
        $this->paymentController = new PaymentController();
        $this->invoiceController = new InvoiceController();
    }

    public function __invoke(Request $request)
    {
        $input = $request->input();

        if (!isset($input['order_id'])) return redirect()->route('account-home');

        if (!session()->has('selected_plan_id')
            && !session()->has('questionare_data')
            && !session()->has('cart')
            && !session()->has('pay_now')) return redirect()->route('account-home');

        $order = Order::where('user_id', auth()->user()->id)->where('id', $input['order_id'])->first();

        if (empty($order)) return redirect()->route('account-home');

        if (session()->has('pay_now')) session()->forget('pay_now');

        if (session('refillRequest')) {
            return (new OrderRefillController())->refillOrder($order, $input['transaction_id']);
        }

        // $sdAPI = new SmartDoctorsApiController();
        $sdCommission = $order->telemedicine_consult;

        $isSubscription = $order->is_subscription;

        // Check if payment success was for changed plan
        $active = 1;
        if (Session::get('change-plan') == 1) {
            $activeOrder = Order::where('user_id', auth()->user()->id)->where('is_active', 1)
                ->orderBy('created_at', 'DESC')
                ->update([
                    'is_changed' => true,
                ]);
            $active = 0;
        }

        $order->update([
            'is_active' => $active,
            'payment_status' => 'Paid',
            'payment_page_success' => true,
            'transaction_id' => $input['transaction_id'],
            'payment_method' => 'Credit Card',
        ]);

        $refillPrice = $order->total_price;

        OrderRefill::create([
            'refill_number' => 0,
            'order_no' => $order->order_no,
            'transaction_id' => $input['transaction_id'],
            'amount' => $refillPrice,
            'shipping_fullname' => $order->shipping_fullname,
            'shipping_address_line' => $order->shipping_address_line,
            'shipping_address_line2' => $order->shipping_address_line2,
            'shipping_city' => $order->shipping_city,
            'shipping_zipcode' => $order->shipping_zipcode,
            'shipping_state' => $order->shipping_state,
            'shipping_phone' => $order->shipping_phone,
            'transaction_type' => $isSubscription ? 'subscription_payment' : 'one_time_payment',
        ]);

        if ($order->status == 'Pending') {
            (new BelugaApiController())->createVisit($order->order_no);
            
        //     $patientSelfie = User::getDocumentPath($order->user_id, 'selfie');
        //     $patientGovtId = User::getDocumentPath($order->user_id, 'govt_id');

        //     $postData = [
        //         'order_id' => $order->id,
        //         'order_no' => $order->order_no,
        //         'user_id' => $order->user_id,
        //         'patient_name' => $order->user->first_name . " " . $order->user->last_name,
        //         'patient_selfie' => getImage($patientSelfie),
        //         'patient_govt_id' => getImage($patientGovtId),
        //         'product_image' => getImage($order->product_image),
        //         'product_name' => $order->product_name,
        //         'product_price' => $order->product_price + $order->shipping_cost,
        //         'product_quantity' => $order->product_quantity,
        //         'payment_status' => 'Paid',
        //         'order_type' => $isSubscription ? 'Subscription' : 'One Time',
        //         'question_ans_id' => $order->question_ans_id,
        //         'shipping_address_line' => $order->shipping_address_line,
        //         'shipping_city' => $order->shipping_city,
        //         'shipping_zipcode' => $order->shipping_zipcode,
        //         'shipping_state' => $order->shipping_state,
        //     ];

        //     // $response = json_decode($sdAPI->createOrder($postData), true);
        }

        if ($isSubscription) {
            $this->handleSubscriptionOrderSuccess($input['order_id'], $sdCommission);

            $invoicePDF = $this->invoiceController->uploadSubscriptionInvoice($input['order_id'], $sdCommission, now()->toDateString());
        } else {
            $this->handleOneTimeOrderSuccess($input['order_id'], $sdCommission);

            $invoicePDF = $this->invoiceController->uploadInvoicePdf($input['order_id'], $sdCommission);
        }

        $order->update([
            'invoice' => $invoicePDF[0],
            'invoice_no' => $invoicePDF[1],
        ]);
        $order->orderRefill()->where('refill_number', 0)->update([
            'invoice' => $invoicePDF[0],
            'invoice_no' => $invoicePDF[1],
        ]);

        if (session()->has('selected_plan_id')) session()->forget('selected_plan_id');
        if (session()->has('selected_plan_qty')) session()->forget('selected_plan_qty');
        if (session()->has('questionare_data')) session()->forget('questionare_data');
        if (session()->has('promoSummary')) session()->forget('promoSummary');
        if (session()->has('cart')) session()->forget('cart');
        if (session()->has('order_id')) session()->forget('order_id');
        if (session()->has('change-plan')) session()->forget('change-plan');

        return redirect()->route('order-success', [$order->order_no]);
    }

    private function handleSubscriptionOrderSuccess($orderId, $sdCommission)
    {
        $order = $this->paymentController->getOrderDetails($orderId);
        $order->telemedConsult = $sdCommission;
        $order->shippingCost = $this->shippingCost;
        $order->discount = $this->shippingCost + $sdCommission;

        if ($order->is_promo_active) {
            $this->paymentController->sendNewOrderWithPromoMail($order);
        } else {
            $this->paymentController->sendNewOrderMail($order);
        }
    }

    private function handleOneTimeOrderSuccess($orderId, $sdCommission)
    {
        $order = $this->paymentController->getOrderDetails($orderId);
        $order->telemedConsult = $sdCommission;
        $order->shippingCost = $this->shippingCost;
        $order->discount = $this->shippingCost + $sdCommission;

        if ($order->is_promo_active) {
            $this->paymentController->sendNewOrderWithPromoMail($order);
        } else {
            $this->paymentController->sendNewOrderMail($order);
        }
    }
}