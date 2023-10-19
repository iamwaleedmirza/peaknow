<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Models\Logs\SubscriptionLogs;
use App\Models\Order;
use App\Http\Controllers\Email\EmailController;

class PauseResumeController extends Controller
{
    public function pause($order_no)
    {
        $order = Order::where('order_no', $order_no)
            ->where('is_active', true)
            ->where('is_subscription', true)
            ->first();

        if (empty($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!',
            ]);
        }

        if (empty($order->subscription)) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found!',
            ]);
        }

        $order->subscription->update([
            'is_paused' => true,
            'updated_by' => 'USER'
        ]);

        SubscriptionLogs::store($order->order_no,'PAUSED', 'USER');

        $paused_date = date('F j, Y');
        $emailManager = new EmailController();
        $emailManager->sendSubscriptionPausedMail($order, $paused_date);

        return response()->json([
            'success' => true,
            'message' => 'Subscription has been paused successfully.',
        ]);
    }

    public function resume($order_no)
    {
        $order = Order::where('order_no', $order_no)
            ->where('is_active', true)
            ->where('is_subscription', true)
            ->first();

        if (empty($order)) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found!',
            ]);
        }

        if (empty($order->subscription)) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found!',
            ]);
        }

        $order->subscription->update([
            'is_paused' => false,
            'updated_by' => 'USER'
        ]);

        SubscriptionLogs::store($order->order_no,'RESUMED', 'USER');

        $resumed_date = date('F j, Y');
        $emailManager = new EmailController();
        $emailManager->sendSubscriptionResumedMail($order, $resumed_date);

        return response()->json([
            'success' => true,
            'message' => 'Subscription has been resumed successfully.',
        ]);
    }
}
