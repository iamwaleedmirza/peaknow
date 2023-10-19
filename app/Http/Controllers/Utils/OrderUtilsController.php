<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderUtilsController extends Controller
{
    public function isRefillEnabled()
    {
        $activeOrder = Order::where('user_id', auth()->user()->id)
            ->where('status', 'Prescribed')
            ->where('is_active', true)
            ->where('is_exhausted', false)
            ->first();

        if ($activeOrder == null || empty($activeOrder->refill_until_date)) {
            return false;
        }

        $lastRefill = $activeOrder->orderRefill()->orderBy('created_at', 'DESC')->first();

        $currentDate = strtotime(date('Y-m-d'));

        $isPrescriptionActive = $currentDate < strtotime($activeOrder->refill_until_date);

        $isRefillsAvailable = $activeOrder->refill_count < 5;

        $isLastRefillShipped = $lastRefill->is_shipped == 1;

        return $isPrescriptionActive && $isRefillsAvailable && $isLastRefillShipped;
    }

    public function getActiveOrder()
    {
        return Order::where('user_id', auth()->user()->id)
            ->where('is_active', true)
            ->first();
    }

    public function isRefillsAvailable($order)
    {
        if ($order == null || empty($order->refill_until_date)) {
            return false;
        }

        $lastRefill = $order->orderRefill()->orderBy('created_at', 'DESC')->first();

        $currentDate = strtotime(date('Y-m-d'));

        $isPrescriptionActive = $currentDate < strtotime($order->refill_until_date);

        $isRefillsAvailable = $order->refill_count < 5;

        $isLastRefillShipped = $lastRefill->is_shipped == 1;

        return $isPrescriptionActive && $isRefillsAvailable && $isLastRefillShipped;
    }
}
