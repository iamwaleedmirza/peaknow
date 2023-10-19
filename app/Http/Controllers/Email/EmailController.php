<?php

namespace App\Http\Controllers\Email;

use Carbon\Carbon;
use App\Models\OrderRefill;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\SendGridController;

class EmailController extends Controller
{
    public function sendSubscriptionPausedMail($order_data, $paused_date)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();

        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'paused_date' => Carbon::parse($paused_date)->format('F j, Y'),
            'manage_plan_action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_SUBSCRIPTION_PAUSED, $emailData, 'subscription-update@peakscurative.com');
    }

    public function sendSubscriptionResumedMail($order_data, $resumed_date)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'resumed_date' => Carbon::parse($resumed_date)->format('F j, Y'),
            'manage_plan_action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_SUBSCRIPTION_RESUMED, $emailData, 'subscription-update@peakscurative.com');
    }

    public function sendSubscriptionCancelledMail($order_data, $cancel_date)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();

        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'cancel_date' => Carbon::parse($cancel_date)->format('F j, Y'),
            'manage_plan_action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_SUBSCRIPTION_CANCELLATION_ADMIN, $emailData, 'subscription-update@peakscurative.com');
    }

    public function sendRefillOrderConfirmation($order_data, $orderRefill)
    {

        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();

        $address = $orderRefill->shipping_address_line2 ? $orderRefill->shipping_address_line . ',<br>' . $orderRefill->shipping_address_line2 : $orderRefill->shipping_address_line;
        $refillConsumed = $orderRefill->refill_number;
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'refill_no' => $orderRefill->refill_number,
            'refill_date' => Carbon::parse($orderRefill->refill_date)->format('F j, Y'),
            'refill_consumed' => $refillConsumed . '-out-of-5', // 2-of-6 , 4-of-6
            'refill_amount' => $orderRefill->amount,
            'shipping_address' => $orderRefill->shipping_fullname . '<br>' . $address . ',<br>' . $orderRefill->shipping_city . ' - ' . $orderRefill->shipping_zipcode . ', ' . $orderRefill->shipping_state,
            'order_total' => $order->total_price,
            'invoice_url' => getImage($orderRefill->invoice),
            'year' => now()->format('Y'),            
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_ORDER_CONFIRMATION, $emailData, 'order-confirm@peakscurative.com');
    }

    public function sendRefillManualShippingConfirmation($order_data, $orderRefill, $trackingNumber, $shipDate)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();

        $address = $orderRefill->shipping_address_line2 ? $orderRefill->shipping_address_line . ',<br>' . $orderRefill->shipping_address_line2 : $orderRefill->shipping_address_line;
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'refill_no' => $orderRefill->refill_number,
            'refill_date' => Carbon::parse($orderRefill->refill_date)->format('F j, Y'),
            'refill_delivered_date' => Carbon::parse($shipDate)->format('F j, Y'),
            'shipping_address' => $orderRefill->shipping_fullname . '<br>' . $address . ',<br>' . $orderRefill->shipping_city . ' - ' . $orderRefill->shipping_zipcode . ', ' . $orderRefill->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_MANUAL_SHIPPING_CONFIRMATION, $emailData, 'order-update@peakscurative.com');
    }

    public function sendRefillShippingConfirmation($order_data, $orderRefill, $trackingNumber, $shipDate)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name')->where('id',$order->user_id)->first();

        $address = $orderRefill->shipping_address_line2 ? $orderRefill->shipping_address_line . ',<br>' . $orderRefill->shipping_address_line2 : $orderRefill->shipping_address_line;
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'tracking_action_url' => "https://www.fedex.com/fedextrack/?trknbr=${trackingNumber}",
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'refill_no' => $orderRefill->refill_number,
            'refill_date' => Carbon::parse($orderRefill->refill_date)->format('F j, Y'),
            'refill_shipped_date' => Carbon::parse($shipDate)->format('F j, Y'),
            'shipping_address' => $orderRefill->shipping_fullname . '<br>' . $address . ',<br>' . $orderRefill->shipping_city . ' - ' . $orderRefill->shipping_zipcode . ', ' . $orderRefill->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_SHIPPING_CONFIRMATION, $emailData, 'order-update@peakscurative.com');
    }

    public function sendOrderPaymentFailedMail($order_data)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'new_order_action_url' => route('account-home'),
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_ORDER_PAYMENT_FAILED, $emailData, 'payments-update@peakscurative.com');
    }

    public function sendRefillPaymentFailedMail($order)
    {
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'my_plan_action_url' => route('user.plan.index'),
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_PAYMENT_FAILED, $emailData, 'payments-update@peakscurative.com');
    }

    public function sendSubscriptionRefillPaymentFailedMail($order)
    {
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'my_plan_action_url' => route('user.plan.index'),
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_SUBSCRIPTION_REFILL_PAYMENT_FAILED, $emailData, 'payments-update@peakscurative.com');
    }

    public function sendsubscribedPopUpMail($user)
    {
        $emailData = [];
        $mailResponse = SendGridController::sendMail($user['email'], TEMPLATE_ID_SUBSCRIBED_USER, $emailData, 'hello@peakscurative.com');
    }

    public function sendContactUsMail($user)
    {
        $mailResponse = SendGridController::sendMail($user['email'], TEMPLATE_ID_CONTACT_US, [], 'hello@peakscurative.com');

    }

    public function sendRefillFailedMailForOneTime($order_data, $refill_number, $refunded_amount)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();

        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'manage_plan_action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'refill_no' => $refill_number,
            'total_refunded_amount' => $refunded_amount,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_FAILED_ONE_TIME, $emailData, 'order-update@peakscurative.com');

    }

    public function sendRefillFailedMailForSubscription($order_data, $refill_number, $refunded_amount)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'order_no' => $order->order_no,
            'manage_plan_action_url' => route('user.plan.index'),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'refill_no' => $refill_number,
            'total_refunded_amount' => $refunded_amount,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_REFILL_FAILED_SUBSCRIPTION, $emailData, 'order-update@peakscurative.com');

    }

    public function sendPlanExpiredMail($order_data)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        
        $emailData = [
            'account_username' => $order->user->first_name . ' ' . $order->user->last_name,
            'manage_plan_action_url' => route('user.plan.index'),
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($order->user->email, TEMPLATE_ID_PLAN_EXPIRED, $emailData, 'notify@peakscurative.com');
    }
}
