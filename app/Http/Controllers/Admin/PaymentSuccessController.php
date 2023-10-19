<?php

namespace App\Http\Controllers\Admin;

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
use App\Http\Controllers\Payment\InvoiceController;

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

    public function StoreData($input)
    {
        if (!isset($input['order_id'])) return redirect()->route('account-home');

        if (!session()->has('selected_plan_id')
            && !session()->has('questionare_data')
            && !session()->has('cart')
            && !session()->has('pay_now')) return redirect()->route('account-home');

        $order = Order::where('id', $input['order_id'])->first();
        
        if (empty($order)) return redirect()->route('account-home');

        if (session()->has('pay_now')) session()->forget('pay_now');

        return (new OrderRefillController())->refillOrder($order, $input['transaction_id']);
    }
}
