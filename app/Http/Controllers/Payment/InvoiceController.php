<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Utils\FileUploadController;
use Carbon\Carbon;
use PDF;

class InvoiceController extends Controller
{
    public function uploadInvoicePdf($order_id, $telemedConsult)
    {
        $order = (array)(new PaymentController())->getOrderDetails($order_id);
        $invoiceNo = rand(1000, 9999);
        $order['invoice_no'] = $order_id . '' . $invoiceNo;
        $order['telemedConsult'] = $telemedConsult;
        $order['shippingCost'] = $order['shipping_cost'];
        $order['discount'] = $order['shipping_cost'] + $telemedConsult;
        $order['trans_date'] = null;

        view()->share('order', $order);
        $pdf = PDF::loadView('payment.invoice.invoice', $order);

        $document = new FileUploadController();
        $name = 'invoice_' . $order_id . '_' . uniqid() . '.' . 'pdf';
        $filePath = $document->uploadInvoice($order, $pdf, 'payment.invoice.invoice', $name, 'invoice');

        return [$filePath, $order['invoice_no']];
    }

    public function uploadSubscriptionInvoice($order_id, $telemedConsult, $trans_date)
    {
        $order = (array)(new PaymentController())->getOrderDetails($order_id);
        $invoiceNo = rand(1000, 9999);
        $order['invoice_no'] = $order_id . '' . $invoiceNo;
        $order['telemedConsult'] = $telemedConsult;
        $order['shippingCost'] = $order['shipping_cost'];
        $order['discount'] = $order['shipping_cost'] + $telemedConsult;
        $order['trans_date'] = $trans_date;
        $order['billing_end_date'] = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($order['created_at'])))->addDays(30);

        view()->share('order', $order);
        $pdf = PDF::loadView('payment.invoice.subscription-invoice', $order);

        $document = new FileUploadController();
        $name = 'subcription_invoice_' . $order_id . '_' . uniqid() . '.' . 'pdf';
        $filePath = $document->uploadInvoice($order, $pdf, 'payment.invoice.subscription-invoice', $name, 'invoice');

        return [$filePath, $order['invoice_no']];
    }
}
