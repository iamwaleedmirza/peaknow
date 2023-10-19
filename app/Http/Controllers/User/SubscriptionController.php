<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;

class SubscriptionController extends Controller
{
    public function myPlan()
    {
        if (auth()->user()->phone_verified == 0) return redirect()->route('otp-verify');
        if (auth()->user()->email_verified == 0) return redirect()->route('email-verify');

        $order = Order::where('user_id', auth()->user()->id)->where('is_active', true)->first();

        if ($order === null) {
            return view('user.dashboard.my-plan.no-active-plan');
        }

        if (!$order->is_subscription) {
            return view('user.dashboard.my-plan.one-time', compact('order'));
        }

        $plan = Controller::PlanDetails($order->plan_id,$order->product_quantity);
        $is_order_exist = $plan['status'];

        if ($order->is_subscription) {
            $subscription = $order->subscription;

            if ($subscription == null) {
                return view('user.dashboard.my-plan.no-active-plan');
            }

            return view('user.dashboard.my-plan.subscription', compact('order', 'subscription','is_order_exist'));
        }
    }

}
