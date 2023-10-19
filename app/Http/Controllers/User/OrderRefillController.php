<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Api\SmartDoctorsApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\Utils\AuthorizeController;
use App\Http\Controllers\Utils\FileUploadController;
use App\Models\Logs\SubscriptionLogs;
use App\Models\Order;
use App\Models\OrderRefill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PDF;
use Auth;

class OrderRefillController extends Controller
{
    public function refillOrder($order, $transactionId)
    {

        $libertyApi = new LibertyApiController();
        $libertyResponse = $libertyApi->submitRefillRequest([["scriptNumber" => $order->script_number]]);

        $refillNumber = $order->orderRefill()->orderBy('created_at', 'DESC')->first()->refill_number + 1;

        $isSubscription = $order->is_subscription;

        if ($libertyResponse->ok()) {

            $response = $libertyResponse->json();

            if ($response && $response[0] && $response[0]['Status'] == 'Success') {
                $order->update([
                    'refill_count' => $refillNumber,
                ]);

                if ($isSubscription) {
                    $order->subscription->update([
                        'next_refill_date' => now()->addDays(27)->toDateString()
                    ]);
                }

                // $sdAPI = new SmartDoctorsApiController();
                // $sdCommission = 0;
                // $sdResponse = json_decode($sdAPI->getSmartDoctorsCommission(), true);
                // if ($sdResponse) {
                //     $sdCommission = $sdResponse['smartdoctor_commission'];
                // }

                $sdCommission = $order->telemedicine_consult;

                $invoiceNo = $order->id . '' . rand(1000, 9999);
                $order->invoice_no = $invoiceNo;
                $order->telemedConsult = $sdCommission;
                $order->shippingCost = $order->shipping_cost;
                $order->discount = $order->shipping_cost + $sdCommission;
                $order->trans_date = now()->toDateTimeString();
                $order->refill_number = $refillNumber;
                $order->refill_date = date('Y-m-d');
                $order->transaction_id = $transactionId;
                $order->refill_end_date = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->addDays(30);
                
                $document = new FileUploadController();

                view()->share('order', $order);
                $name = 'refill_invoice_' . $order->id . '_' . uniqid() . '.' . 'pdf';

                if ($isSubscription) {
                    $pdfInvoice = PDF::loadView('payment.invoice.subscription-refill-invoice', (array)$order);
                    $filePath = $document->uploadInvoice($order, $pdfInvoice, 'payment.invoice.subscription-refill-invoice', $name, 'invoice');
                } else {
                    $pdfInvoice = PDF::loadView('payment.invoice.one-time-refill-invoice', (array)$order);
                    $filePath = $document->uploadInvoice($order, $pdfInvoice, 'payment.invoice.one-time-refill-invoice', $name, 'invoice');
                }

                $refillPrice = $order->total_price;

                $orderRefill = OrderRefill::create([
                    'order_no' => $order->order_no,
                    'refill_number' => $refillNumber,
                    'refill_status' => 'Confirmed',
                    'refill_date' => date('Y-m-d'),
                    'transaction_id' => $transactionId,
                    'transaction_type' => $isSubscription ? 'subscription_payment' : 'one_time_payment',
                    'amount' => $refillPrice,
                    'shipping_fullname' => $order->shipping_fullname,
                    'shipping_address_line' => $order->shipping_address_line,
                    'shipping_address_line2' => $order->shipping_address_line2,
                    'shipping_city' => $order->shipping_city,
                    'shipping_zipcode' => $order->shipping_zipcode,
                    'shipping_state' => $order->shipping_state,
                    'shipping_phone' => $order->shipping_phone,
                    'invoice' => $filePath,
                    'invoice_no' => $invoiceNo,
                ]);

                $order = Order::find($order->id);
                $emailManager = new EmailController();
                $emailManager->sendRefillOrderConfirmation($order, $orderRefill);
                if (session('refillRequest')) session()->forget('refillRequest');

                if(Auth::check()){
                    if(Auth::user()->u_type!='patient'){
                        if (!$isSubscription) {
                            return response()->json(['status' => true,'message' => 'Refill generated successfully']);
                        } else {
                            return response()->json(['status' => true,'message' => 'Refill generated successfully']);
                        }
                    } else {
                        if (!$isSubscription) {
                            return redirect()->route('refill.request-success', ['orderNo' => $order->order_no]);
                        } else {
                            return true;
                        }    
                    }
                } else {
                    return true;
                }
                

            } else {
                $message = "Something went wrong, please try again later.";
                $refundReason = 'Something went wrong. Error code: ' . $libertyResponse->status();

                if ($response && $response[0] && $response[0]['Status'] == 'Refill_Request_Pending') {
                    $message = 'Your previous refill request is pending or not yet shipped, please try again later.';
                    $refundReason = $response[0]['Status'];
                }

                $order = Order::find($order->id);
                $this->refundOnLibertyFailure($order, $transactionId, $refillNumber, $refundReason);

                if(Auth::check() && Auth::user()->u_type!='patient'){
                    $updated_by = 'ADMIN';
                } else {
                    $updated_by = 'SYSTEM';
                }

                if ($isSubscription) {
                    $order->subscription->update([
                        'is_paused' => true,
                        'updated_by' => $updated_by
                    ]);
                    SubscriptionLogs::store($order->order_no, 'PAUSED', $updated_by);
                    if(Auth::check()){
                        if(Auth::user()->u_type!='patient'){
                            return response()->json(['status' => false,'message' => 'Refill is not generated on liberty and amount will be refunded.','code' => $response[0]['Status']]);
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }
                    
                } else {
                    if(Auth::check()){
                        if(Auth::user()->u_type!='patient'){
                            return response()->json(['status' => false,'message' => 'Refill is not generated on liberty and amount will be refunded.','code' => $response[0]['Status']]);
                        } else {
                            return redirect()->route('account-home')->with(['error_title' => Error_3003_Title, 'error_description' => Error_3003_Description]);    
                        }
                    } else {
                        return true;
                    }
                }
            }

        } else {
            if ($libertyResponse->status() == 401) {
                $refundReason = 'Something went wrong. Error code: 401: Liberty Unauthorized.';
                $errorCode = 'Liberty Unauthorized.';
            } else {
                $refundReason = 'Something went wrong. Error code: ' . $libertyResponse->status();
                $errorCode = $libertyResponse->status();
            }

            $order = Order::find($order->id);
            $this->refundOnLibertyFailure($order, $transactionId, $refillNumber, $refundReason);

            if ($isSubscription) {
                if(Auth::check() && Auth::user()->u_type!='patient'){
                    $updated_by = 'ADMIN';
                } else {
                    $updated_by = 'SYSTEM';
                }

                $order->subscription->update([
                    'is_paused' => true,
                    'updated_by' => $updated_by
                ]);
                SubscriptionLogs::store($order->order_no, 'PAUSED', $updated_by);
                if(Auth::check()){
                    if(Auth::user()->u_type!='patient'){
                        return response()->json(['status' => false,'message' => 'Refill is not generated on liberty and amount will be refunded.','code' => $errorCode]);
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }
            } else {
                if(Auth::check()){
                    if(Auth::user()->u_type!='patient'){
                        return response()->json(['status' => false,'message' => 'Refill is not generated on liberty and amount will be refunded','code' => $errorCode]);
                    } else {
                        return redirect()->route('account-home')->with(['error_title' => Error_3003_Title, 'error_description' => Error_3003_Description]);
                    }
                } else {
                    return true;
                }
            }
        }
    }

    private function refundOnLibertyFailure($order, $transactionId, $refillNumber, $refundReason)
    {
        $amount = $order->total_price;
        $authorizeController = new AuthorizeController();
        $refundResp = $authorizeController->refundTransaction($transactionId, $amount, $order->order_no, $refillNumber, 1, $refundReason, false);

        if ($refundResp == 'success') {
            Log::debug('Order has been Refunded successfully.');
        } else {
            Log::debug('Refund failed!!!');
        }

        $emailManager = new EmailController();

        if ($order->is_subscription) {
            $emailManager->sendRefillFailedMailForSubscription($order, $refillNumber, $amount);
        } else {
            $emailManager->sendRefillFailedMailForOneTime($order, $refillNumber, $amount);
        }

        if (session('refillRequest')) session()->forget('refillRequest');
    }
}