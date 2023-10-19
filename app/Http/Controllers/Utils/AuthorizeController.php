<?php

namespace App\Http\Controllers\Utils;

use App\Enums\Log\PaymentLogEvent;
use App\Enums\Log\Status;
use App\Models\Logs\PaymentLog;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;

use App\Models\OrderRefill;
use App\Models\GeneralSetting;
use App\Models\OrderRefundHistory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionPaymentMail;
use Illuminate\Support\Facades\Storage;
use net\authorize\api\contract\v1 as AnetAPI;
use App\Http\Controllers\Email\EmailController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Api\LibertyApiController;
use net\authorize\api\controller as AnetController;

class AuthorizeController extends Controller
{
    public $shippingCost = 0;
    public function __construct()
    {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
    }
    function refundTransaction($refTransId, $amount, $order_id, $refillNumber = 0, $automate = 0, $reason = null, $is_refunded = true)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        //        first to check transaction status
        $check_request = new AnetAPI\GetTransactionDetailsRequest();
        $check_request->setMerchantAuthentication($merchantAuthentication);
        $check_request->setTransId($refTransId);
        $check_controller = new AnetController\GetTransactionDetailsController($check_request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response2 = $check_controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response2 = $check_controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }
        $transaction_status = $response2->getTransaction()->getTransactionStatus();
        if ($transaction_status != 'settledSuccessfully') {
            return $response_return = $this->voidTransaction($refTransId, $amount, $order_id, $refillNumber, $automate, $reason, $is_refunded);
        } else {
            if ($response2->getTransaction()->getPayment()->getCreditCard()) {
                $card_last_four_digits = $response2->getTransaction()->getPayment()->getCreditCard()->getCardNumber();
            } else {
                $card_last_four_digits = $response2->getTransaction()->getPayment()->getBankAccount()->getAccountNumber();
            }
            $card_last_four_digits = substr($card_last_four_digits, -4);
            // Set the transaction's refId
            $refId = $refTransId;
            if ($response2->getTransaction()->getPayment()->getCreditCard()) {
                // Create the payment data for a credit card
                $creditCard = new AnetAPI\CreditCardType();
                $creditCard->setCardNumber($card_last_four_digits);
                $creditCard->setExpirationDate("XXXX");
                $paymentOne = new AnetAPI\PaymentType();
                $paymentOne->setCreditCard($creditCard);
            } else {
                // Create the payment data for a Bank Account
                $bankAccount = new AnetAPI\BankAccountType();
                //                $bankAccount->setAccountType($response2->getTransaction()->getPayment()->getBankAccount()->getAccountType());
                // see eCheck documentation for proper echeck type to use for each situation
                //                $bankAccount->setEcheckType('WEB');
                $bankAccount->setAccountNumber($card_last_four_digits);
                $routing_no = substr($response2->getTransaction()->getPayment()->getBankAccount()->getRoutingNumber(), -4);
                $bankAccount->setRoutingNumber($routing_no);
                //                $bankAccount->setNameOnAccount($response2->getTransaction()->getPayment()->getBankAccount()->getNameOnAccount());
                //                $bankAccount->setBankName($response2->getTransaction()->getPayment()->getBankAccount()->getBankName());
                $paymentOne = new AnetAPI\PaymentType();
                $paymentOne->setBankAccount($bankAccount);
            }
            //create a transaction
            $transactionRequest = new AnetAPI\TransactionRequestType();
            $transactionRequest->setTransactionType("refundTransaction");
            $transactionRequest->setAmount($amount);
            $transactionRequest->setPayment($paymentOne);
            $transactionRequest->setRefTransId($refTransId);

            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequest);
            $controller = new AnetController\CreateTransactionController($request);
            if (env('AUTHORIZE_MODE') == 'SANDBOX') {
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
            } else {
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            }
            if ($response != null) {
                if ($response->getMessages()->getResultCode() == "Ok") {
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getMessages() != null) {
                        $order = Order::where('order_no', $order_id)->first();
                        $order->update(['is_refunded' => $is_refunded]);
                      
                        //$order->update(['status' => 'Refunded']);
                        OrderRefundHistory::create([
                            'order_no' => $order->order_no,
                            'refill_number' => $refillNumber,
                            'transaction_id' => $tresponse->getTransId(),
                            'amount' => $amount,
                            'refund_reason' => $reason,
                            'is_auto_refunded' => $automate
                        ]);
                        try {
                            PaymentLog::create([
                                'user_id' => $order->user_id ?? null,
                                'order_no' => $order->order_no ?? null,
                                'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                                'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                                'status' => Status::SUCCESS,
                                'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                                'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                                'transaction_code' => $tresponse->getMessages()[0]->getCode() ?? null,
                                'transaction_message' => $tresponse->getMessages()[0]->getDescription() ?? null,
                                'transaction_id' => $tresponse->getTransId(),
                                'amount' => $amount,
                            ]);
                        } catch (\Exception $e) {
                            Log::error($e->getMessage());
                        }

                        $response_return = "success";

                    } else {
                        $response_return = "failed";
                        if ($tresponse->getErrors() != null) {
                            $response_return = "failed";
                            Log::debug('Authorize Error: '.json_encode($tresponse->getErrors()));
                            $order = Order::where('order_no', $order_id)->first();
                            OrderRefundHistory::create([
                                'order_no' => $order->order_no,
                                'refill_number' => $refillNumber,
                                'transaction_id' => $refTransId,
                                'amount' => $amount,
                                'status' => 0,
                                'failure_reason' => json_encode($tresponse->getErrors()),
                                'is_auto_refunded' => 0
                            ]);

                            try {
                                PaymentLog::create([
                                    'user_id' => $order->user_id ?? null,
                                    'order_no' => $order->order_no ?? null,
                                    'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                                    'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                                    'status' => Status::FAILURE,
                                    'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                                    'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                                    'transaction_code' => $tresponse->getErrors()[0]->getErrorCode() ?? null,
                                    'transaction_message' => $tresponse->getErrors()[0]->getErrorText() ?? null,
                                    'transaction_id' => $refTransId,
                                    'amount' => $amount,
                                ]);
                            } catch (\Exception $e) {
                                Log::error($e->getMessage());
                            }
                        }
                    }
                } else {
                    $response_return = "failed";
                    $tresponse = $response->getTransactionResponse();
                    if ($tresponse != null && $tresponse->getErrors() != null) {
                        $response_return = "failed";
                        Log::debug('Authorize Error: '.json_encode($tresponse->getErrors()));
                        $order = Order::where('order_no', $order_id)->first();

                        OrderRefundHistory::create([
                            'order_no' => $order->order_no,
                            'refill_number' => $refillNumber,
                            'transaction_id' => $refTransId,
                            'amount' => $amount,
                            'status' => 0,
                            'failure_reason' => json_encode($tresponse->getErrors()),
                            'is_auto_refunded' => 0
                        ]);
                    } else {
                        $response_return = "failed";
                        $order = Order::where('order_no', $order_id)->first();

                        OrderRefundHistory::create([
                            'order_no' => $order->order_no,
                            'refill_number' => $refillNumber,
                            'transaction_id' => $refTransId,
                            'amount' => $amount,
                            'status' => 0,
                            'failure_reason' => 'No response from authorize',
                            'is_auto_refunded' => 0
                        ]);
                    }
                    try {
                        PaymentLog::create([
                            'user_id' => $order->user_id ?? null,
                            'order_no' => $order->order_no ?? null,
                            'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                            'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                            'status' => Status::FAILURE,
                            'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                            'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                            'transaction_code' => $tresponse->getErrors()[0]->getErrorCode() ?? null,
                            'transaction_message' => $tresponse->getErrors()[0]->getErrorText() ?? null,
                            'transaction_id' => $refTransId,
                            'amount' => $amount,
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                    }
                }
            } else {
                $response_return = "failed";
                $order = Order::where('order_no', $order_id)->first();
                OrderRefundHistory::create([
                    'order_no' => $order->order_no,
                    'refill_number' => $refillNumber,
                    'transaction_id' => $refTransId,
                    'amount' => $amount,
                    'status' => 0,
                    'failure_reason' => 'No response from authorize',
                    'is_auto_refunded' => 0
                ]);
            }
            return $response_return;
        }
    }

    function voidTransaction($transactionid, $amount, $order_id, $refillNumber, $automate, $reason = null, $is_refunded = true)
    {
        /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('MERCHANT_TRANSACTION_KEY'));
        // Set the transaction's refId
        $refId = $transactionid;
        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("voidTransaction");
        $transactionRequestType->setRefTransId($transactionid);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        if (env('AUTHORIZE_MODE') == 'SANDBOX') {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        } else {
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        }

        if ($response != null) {
            if ($response->getMessages()->getResultCode() == "Ok") {
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getMessages() != null) {
                    $order = Order::where('order_no', $order_id)->first();
                    $order->update(['is_refunded' => $is_refunded]);
                   
                    OrderRefundHistory::create([
                        'order_no' => $order->order_no,
                        'refill_number' => $refillNumber,
                        'transaction_id' => $tresponse->getTransId(),
                        'amount' => $amount,
                        'refund_reason' => $reason,
                        'is_auto_refunded' => $automate
                    ]);
                    $response_return = "success";

                    try {
                        PaymentLog::create([
                            'user_id' => $order->user_id ?? null,
                            'order_no' => $order->order_no ?? null,
                            'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                            'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                            'status' => Status::SUCCESS,
                            'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                            'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                            'transaction_code' => $tresponse->getMessages()[0]->getCode() ?? null,
                            'transaction_message' => $tresponse->getMessages()[0]->getDescription() ?? null,
                            'transaction_id' => $transactionid,
                            'amount' => $amount,
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                    }

                } else {
                    $response_return = "failed";
                    if ($tresponse->getErrors() != null) {
                        $response_return = "failed";
                        $order = Order::where('order_no', $order_id)->first();

                        OrderRefundHistory::create([
                            'order_no' => $order->order_no,
                            'refill_number' => $refillNumber,
                            'transaction_id' => $transactionid,
                            'amount' => $amount,
                            'status' => 0,
                            'failure_reason' => @$tresponse->getMessages()[0]->getDescription() ?? null,
                            'is_auto_refunded' => 0
                        ]);

                        try {
                            PaymentLog::create([
                                'user_id' => $order->user_id ?? null,
                                'order_no' => $order->order_no ?? null,
                                'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                                'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                                'status' => Status::FAILURE,
                                'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                                'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                                'transaction_code' => $tresponse->getMessages()[0]->getCode() ?? null,
                                'transaction_message' => $tresponse->getMessages()[0]->getDescription() ?? null,
                                'transaction_id' => $transactionid,
                                'amount' => $amount,
                            ]);
                        } catch (\Exception $e) {
                            Log::error($e->getMessage());
                        }

                    }
                }
            } else {
                $response_return = "failed";
                $tresponse = $response->getTransactionResponse();
                if ($tresponse != null && $tresponse->getErrors() != null) {
                    $response_return = "failed";
                    $order = Order::where('order_no', $order_id)->first();

                    OrderRefundHistory::create([
                        'order_no' => $order->order_no,
                        'refill_number' => $refillNumber,
                        'transaction_id' => $transactionid,
                        'amount' => $amount,
                        'status' => 0,
                        'failure_reason' => json_encode(@$tresponse->getErrors()),
                        'is_auto_refunded' => 0
                    ]);
                } else {
                    $response_return = "failed";
                    $order = Order::where('order_no', $order_id)->first();

                    OrderRefundHistory::create([
                        'order_no' => $order->order_no,
                        'refill_number' => $refillNumber,
                        'transaction_id' => $transactionid,
                        'amount' => $amount,
                        'status' => 0,
                        'failure_reason' => 'No response from authorize',
                        'is_auto_refunded' => 0
                    ]);
                }



                try {
                    PaymentLog::create([
                        'user_id' => $order->user_id ?? null,
                        'order_no' => $order->order_no ?? null,
                        'payment_for' => ($refillNumber == 0) ? 'NEW FILL' : "REFILL $refillNumber",
                        'event_type' => PaymentLogEvent::REFUND_TRANSACTION,
                        'status' => Status::FAILURE,
                        'response_code' => $response->getMessages()->getMessage()[0]->getCode() ?? null,
                        'response_message' => $response->getMessages()->getMessage()[0]->getText() ?? null,
                        'transaction_code' => $tresponse->getMessages()[0]->getCode() ?? null,
                        'transaction_message' => $tresponse->getMessages()[0]->getDescription() ?? null,
                        'transaction_id' => $transactionid,
                        'amount' => $amount,
                    ]);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

            }
        } else {
            $response_return = "failed";
            $order = Order::where('order_no', $order_id)->first();

            OrderRefundHistory::create([
                'order_no' => $order->order_no,
                'refill_number' => $refillNumber,
                'transaction_id' => $transactionid,
                'amount' => $amount,
                'status' => 0,
                'failure_reason' => 'No response from authorize',
                'is_auto_refunded' => 0
            ]);
        }
        return $response_return;
    }
}
