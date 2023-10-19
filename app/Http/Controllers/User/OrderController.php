<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Api\SmartDoctorsApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\OrderUtilsController;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\OrderRefill;
use App\Models\OrderShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\SendGridController;

class OrderController extends Controller
{
    public $shippingCost = 0;

    public function __construct()
    {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
    }

    public function accountOrders()
    {
        if (auth()->user()->phone_verified == 0) {
            return redirect()->route('otp-verify');
        }

        if (auth()->user()->email_verified == 0) return redirect()->route('email-verify');

        $orders = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

        return view('user.dashboard.orders.index', compact('orders'));
    }

    public function orderDetails($orderId)
    {
        $order = Order::where('order_no', $orderId)->where('user_id', Auth::user()->id)->first();

        if (!$order) {
            return abort(404);
        }

        $order->telemedConsult = $order->telemedicine_consult;
        $order->shippingCost = $order->shipping_cost;
        $order->discount = $order->shipping_cost + $order->telemedConsult;

        $order->plan_detail = json_decode($order->plan_detail);

        $orderShipment = OrderShipment::where('order_no', $orderId)->where('refill_number', 0)->first();

        return view('user.dashboard.orders.details', compact('order', 'orderShipment'));
    }

    public function getOrderSuccessView(Request $request,$order)
    {
        $order = Order::where('user_id', auth()->user()->id)->where('order_no', $order)->first();
        if (!$order) {
           abort(404);
        }
        return view('user.onboard.order-success', compact('order'))->with('successful', 'Payment completed successfully.');
    }

    public function refillOrderSuccessView($orderNo)
    {
        $order = Order::where('user_id', auth()->user()->id)->where('order_no', $orderNo)->first();

        if (empty($order)) abort(404);

        return view('payment.refill-payment-success', compact('order'));
    }

    public function refillDetails($orderId, $refillNo)
    {
        $order = OrderRefill::from('order_refills as orf')
            ->where('orf.order_no', $orderId)
            ->where('refill_number', $refillNo)
            ->join('orders as o', 'orf.order_no', '=', 'o.order_no')
            ->select(['orf.id', 'orf.order_no', 'orf.refill_number', 'orf.refill_date', 'orf.refill_status', 'orf.transaction_id', 'orf.transaction_type', 'orf.amount',
                'orf.invoice as refill_invoice', 'orf.invoice_no as refill_invoice_no', 'o.plan_id', 'o.status', 'o.product_image',
                'o.product_name', 'o.is_subscription', 'o.product_price',
                'o.prescribed_date', 'o.script_number', 'o.refill_count', 'orf.shipping_fullname',
                'orf.shipping_address_line', 'orf.shipping_address_line2', 'orf.shipping_city', 'orf.shipping_zipcode',
                'orf.shipping_state', 'orf.shipping_phone', 'o.invoice as order_invoice',
                'o.invoice_no as order_invoice_no', 'o.transaction_id as order_transaction_id', 'orf.created_at',
                'orf.is_shipped','o.total_price','o.product_total_price','o.plan_discount','o.medicine_variant','o.plan_name','o.plan_title','o.strength','o.product_quantity','o.is_promo_active','o.promo_code','o.promo_discount_percent','o.promo_discount'])
            ->first();

        if ($order == null) {
            abort(404);
        }

        $orderShipment = $order->refillShipment();
        $order->plan_detail = json_decode($order->plan_detail);
        return view('user.dashboard.orders.refill-details', compact('order', 'orderShipment'));
    }

    public function requestOrderCancellation(Request $request)
    {
        try {
            $order = Order::where('id', $request->order_id)
                ->where('status', 'Pending')
                ->where('payment_status', 'Paid')
                ->first();

            if (empty($order)) {
                return back()->with('error', 'Order not found.');
            }

            if ($order->payment_status == 'Paid') {
                $response = $this->cancelOrderOnSD($order->order_no);
                if (!$response) {
                    return back()->with(['error_title' => Error_1002_Title, 'error_description' => Error_1002_Description]);
                }
            }

            if ($request->cancel_reason === 'others') {
                $cancelReason = $request->cancel_reason_other;
            } else {
                $cancelReason = $request->cancel_reason;
            }

            $order->update([
                'cancellation_request' => true,
                'cancellation_request_date' => date('Y-m-d H:i:s'),
                'is_active' => 0,
                'cancel_reason' => $cancelReason,
            ]);

            $message = 'Order cancellation request has been generated we will inform you shortly via email.';

            /*
            if ($order->payment_status == 'Unpaid') {
                if ($order->is_subscription) {
                    $subscription = $order->subscription;
                    if ($subscription) {
                        $result = (new PaymentController())->cancelSubscription($subscription->subscription_id);
                        if ($result == 'success') {
                            $subscription->update(['active_status' => 0]);

                            $order->update([
                                'telemedicine_consult' => 0,
                                'status' => 'Cancellation Request',
                                'is_active' => false
                            ]);

                            $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
                            if ($orderRefill) {
                                $orderRefill->update([
                                    'refill_status' => 'Cancelled',
                                ]);
                            }

                            $message = 'The amount will be refunded to your original payment method (if paid).';

                            // Send mail to the user
                            $user = $order->user;
                            $order->full_name = $user->first_name . ' ' . $user->last_name;
                            PaymentController::sendCancelOrderMail($order, $user);

                        } else {
                            return back()->with(['error_title' => Error_2004_Title, 'error_description' => Error_2004_Description]);
                        }
                    }
                }
            }
            */

            // Check if order was changed order
            $this->updateChangedOrderStatus($order);

            return back()->with('success', $message);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function sendCancellationRequestMailToBeluga($masterId, $patientName)
    {
        $toMail = config('services.beluga.cancel_email');
        $subject = "Customer Service Email for Visit Id: $masterId";
        $emailBody = "<p>Patient <strong>$patientName</strong> requested a cancellation for this visit.</p>";

        // $mailResponse = (new PostmarkApiService())->sendMail($toMail, $subject, $emailBody);
        
        try {
            $emailData = [
                'patient_name' => $patientName,
                'master_id' => $masterId
            ];
            $mailResponse = SendGridController::sendMail(config('services.beluga.cancel_email'), TEMPLATE_ID_CANCEL_ORDER_STATUS_FROM_WEBHOOK, $emailData, 'support@peakscurative.com');
            // $mailResponse = (new PostmarkApiService())->sendMail($toMail, $subject, $emailBody);    
        } catch (\Throwable $e) {
            //  Error
        }

        // if ($mailResponse->ok()) {
        //     BelugaMailLog::createLog($masterId, $toMail, $responseData['SubmittedAt'], $responseData['MessageID'], $responseData['ErrorCode'], $responseData['Message']);
        // } else {
        //     $logResponseBody = $mailResponse->body();
        //     Log::error("EMAIL SENDING FAILED", ["Cancellation request failed for peaks order masterID"=> $masterId,"toEmail" => $toMail, "Response received from mail server" => $logResponseBody]);
        //     BelugaMailLog::createLog($masterId, $toMail, null, null, $responseData['ErrorCode'], $responseData['Message']);
        // }
    }

    public function updateChangedOrderStatus($order): void
    {
        $changedOrder = Order::where('user_id', $order->user_id)
            ->where('is_active', true)
            ->where('is_changed', true)
            ->first();

        if ($changedOrder) {
            $changedOrder->update([
                'is_changed' => false
            ]);
        }
    }

//     public function cancelOrder(Request $request)
//     {
//         $order = Order::where('id', $request->order_id)
//             ->where('status', 'Pending')
// //            ->where('payment_status', 'Paid')
//             ->first();

//         if (empty($order)) {
//             return back()->with('error', 'Record not found.');
//         }

//         if ($order->payment_status == 'Paid') {
//             $response = $this->cancelOrderOnSD($order->order_no);

//             if ($response) {
//                 $this->updateOrderAndRefill($request, $order);
//                 $refund = new FinanceController;
//                 $refund->refundOrderById($order->order_no,0,'Cancelled by user');
//                 $message = 'The amount will be refunded to your original payment method.';

//                 return back()->with('success', $message);

//             } else {
//                 return back()->with(['error_title'=>Error_1002_Title,'error_description'=>Error_1002_Description]);
//             }

//         } else {
//             $this->updateOrderAndRefill($request, $order);
//             $message = 'You will receive confirmation on your email shortly.';
//             return back()->with('success', $message);
//         }

//     }

    public function updateOrderAndRefill(Request $request, $order)
    {
        if ($request->cancel_reason === 'others') {
            $cancelReason = $request->cancel_reason_other;
        } else {
            $cancelReason = $request->cancel_reason;
        }

        $order->update([
            'telemedicine_consult' => 0,
            'status' => 'Cancelled',
            'cancel_reason' => $cancelReason,
            'is_active' => false
        ]);

        $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
        if ($orderRefill) {
            $orderRefill->update([
                'refill_status' => 'Cancelled',
            ]);
        }

        // Send mail to the user
        $user = $order->user;
        $order->full_name = $user->first_name . ' ' . $user->last_name;
        // Mail::to($user->email)->send(new OrderCancelledMail($order));
        PaymentController::sendCancelOrderMail($order, $user);

        // Check if order was changed order
        $this->updateChangedOrderStatus($order);
    }

    public function cancelOrderOnSD($orderId)
    {
        try {
            $order = Order::where('order_no', $orderId)->first();

            if (empty($order)) {
                return response()->json([
                    "message" => 'Order not found.'
                ], 404);
            }

            if (!empty($order->belugaOrder->visitId)) {
                $masterId = 'PC-' . $order->order_no;
                $patient_name = $order->user->first_name.' '.$order->user->last_name;
                $emailData = [
                    'patient_name' => $patient_name,
                    'master_id' => $masterId
                ];
                $mailResponse = SendGridController::sendMail(config('services.beluga.cancel_email'), TEMPLATE_ID_CANCEL_ORDER_STATUS_FROM_WEBHOOK, $emailData, 'support@peakscurative.com');
            }

            return response()->json([
                "message" => "Order status updated successfully."
            ], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
        // $site_url = url('/');
        // $accessToken = array(
        //     'authorization' => env('PEAKS_API_ORDER_TOKEN'),
        //     'site_url' => $site_url
        // );
        // $accessToken = json_encode($accessToken);

        // $postData = ['order_id' => $orderId, 'status' => 'Cancellation Request'];

        // try {
        //     $response = Http::withHeaders([
        //         "Accept" => "application/json",
        //         "Authorization" => $accessToken
        //     ])->post(env('SD_API_URL') . 'api/change-order-status', $postData);

        //     if ($response->status() == 200) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     return false;
        // }
    }

    public function requestForRefill()
    {
        $refillHandler = new OrderUtilsController();
        if ($refillHandler) {
            $activeOrder = Order::where('user_id', auth()->user()->id)
                ->where('is_active', true)
                ->first();

            if ($activeOrder) {
                session()->put('refillRequest', true);
                session()->put('order_id', $activeOrder->id);
                session()->put('pay_now', true); // For redirecting to payment page directly

                return (new PaymentController())->handlePaymentWithCustomerProfile(
                    auth()->user()->customer_profile_id,
                    auth()->user()->payment_profile_id,
                    $activeOrder,
                    true
                );
//                return redirect()->route('make-payment');
            }
        }

        return redirect()->route('user.plan.index');
    }
}
