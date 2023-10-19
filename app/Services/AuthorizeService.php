<?php


namespace App\Services;

use App\Enums\Log\PaymentLogEvent;
use App\Enums\Log\Status;
use App\Models\Logs\AuthorizeCustomerLogs;
use App\Models\Logs\PaymentLog;
use Exception;
use Log;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class AuthorizeService
{
    public function createCustomerProfile($user, $order)
    {
        // Create a merchantAuthenticationType object with authentication details retrieved from the config file
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.merchant_login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.merchant_transaction_key'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        // Create the Bill To info for new payment type
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setFirstName($user->first_name);
        $billTo->setLastName($user->last_name);
        $billTo->setAddress($order->shipping_address_line . ' ' . $order->shipping_address_line2);
        $billTo->setCity($order->shipping_city);
        $billTo->setState($order->shipping_state);
        $billTo->setZip($order->shipping_zipcode);
        $billTo->setPhoneNumber($user->phone);
        $billTo->setCountry("USA");

        // Create a new CustomerProfileType and add the payment profile object
        $customerProfile = new AnetAPI\CustomerProfileType();
        $customerProfile->setDescription("Make customer profile to manage payment from peaks.");
        $customerProfile->setMerchantCustomerId("M_" . time());
        $customerProfile->setEmail($user->email);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setProfile($customerProfile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);

        $authorizeMode = config('services.authorize.mode') == 'SANDBOX' ? ANetEnvironment::SANDBOX : ANetEnvironment::PRODUCTION;

        $response = $controller->executeWithApiResponse($authorizeMode);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $customerProfileId = $response->getCustomerProfileId();

            $user->update([
                'customer_profile_id' => $customerProfileId,
            ]);

            try {
                AuthorizeCustomerLogs::create([
                    'user_id' => auth()->user()->id,
                    'event_type' => PaymentLogEvent::CREATE_CUSTOMER_PROFILE,
                    'status' => Status::SUCCESS,
                    'response_code' => $response->getMessages()->getMessage()[0]->getCode(),
                    'response_message' => $response->getMessages()->getMessage()[0]->getText(),
                ]);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return [
                'success' => true,
                'customer_profile_id' => $customerProfileId,
            ];

        } else {
            $errorCode = $response->getMessages()->getMessage()[0]->getCode();
            $errorMessage = $response->getMessages()->getMessage()[0]->getText();

            try {
                AuthorizeCustomerLogs::create([
                    'user_id' => auth()->user()->id,
                    'event_type' => PaymentLogEvent::CREATE_CUSTOMER_PROFILE,
                    'status' => Status::FAILURE,
                    'response_code' => $response->getMessages()->getMessage()[0]->getCode(),
                    'response_message' => $response->getMessages()->getMessage()[0]->getText(),
                ]);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }

            return [
                'success' => false,
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
            ];
        }
    }

    private function chargeCustomerProfile($customerProfileId, $paymentProfileId, $amount)
    {
        // Create a merchantAuthenticationType object with authentication details retrieved from the config file
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize.merchant_login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize.merchant_transaction_key'));

        // Set the transaction's refId
        $refId = 'ref' . time();

        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($customerProfileId);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($paymentProfileId);
        $profileToCharge->setPaymentProfile($paymentProfile);

        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);

        $authorizeMode = config('services.authorize.mode') == 'SANDBOX' ? ANetEnvironment::SANDBOX : ANetEnvironment::PRODUCTION;

        $response = $controller->executeWithApiResponse($authorizeMode);

        if (empty($response)) {
            return [
                'success' => false,
                'response_code' => null,
                'response_message' => null,
                'error_code' => 0,
                'error_message' => 'Custom error: response empty.',
            ];
        }

        $responseCode = $response->getMessages()->getMessage()[0]->getCode() ?? null;
        $responseMessage = $response->getMessages()->getMessage()[0]->getText() ?? null;

        if ($response->getMessages()->getResultCode() == "Ok") {
            $transactionResponse = $response->getTransactionResponse();

            if ($transactionResponse != null && $transactionResponse->getMessages() != null) {
                return [
                    'success' => true,
                    'response_code' => $responseCode,
                    'response_message' => $responseMessage,
                    'transaction_code' => $transactionResponse->getMessages()[0]->getCode(),
                    'transaction_message' => $transactionResponse->getMessages()[0]->getDescription(),
                    'transaction_id' => $transactionResponse->getTransId(),
                ];
            } else {
                if ($transactionResponse->getErrors() != null) {
                    return [
                        'success' => false,
                        'response_code' => $responseCode,
                        'response_message' => $responseMessage,
                        'error_code' => $transactionResponse->getErrors()[0]->getErrorCode(),
                        'error_message' => $transactionResponse->getErrors()[0]->getErrorText(),
                    ];
                } else {
                    return [
                        'success' => false,
                        'response_code' => $responseCode,
                        'response_message' => $responseMessage,
                        'error_code' => 0,
                        'error_message' => 'Custom error: errors empty.',
                    ];
                }
            }
        } else {
            $transactionResponse = $response->getTransactionResponse();
            if ($transactionResponse != null && $transactionResponse->getErrors() != null) {
                return [
                    'success' => false,
                    'response_code' => $responseCode,
                    'response_message' => $responseMessage,
                    'error_code' => $transactionResponse->getErrors()[0]->getErrorCode(),
                    'error_message' => $transactionResponse->getErrors()[0]->getErrorText(),
                ];
            } else {
                return [
                    'success' => false,
                    'response_code' => $responseCode,
                    'response_message' => $responseMessage,
                    'error_code' => 0,
                    'error_message' => 'Custom error: empty transaction response.',
                ];
            }
        }
    }

    public function makeTransaction($customerProfileId, $paymentProfileId, $order, $isRefill)
    {
        if ($isRefill) {
            $amount = $order->total_price;
            $refillNumber = $order->orderRefill()->orderBy('created_at', 'DESC')->first()->refill_number + 1;
            $paymentFor = "REFILL $refillNumber";

        } else {
            $paymentFor = 'NEW FILL';
        }
        $amount = $order->total_price;

        $response = $this->chargeCustomerProfile($customerProfileId, $paymentProfileId, $amount);

        try {
            PaymentLog::create([
                'user_id' => $order->user_id ?? null,
                'order_no' => $order->order_no ?? null,
                'payment_for' => $paymentFor,
                'event_type' => PaymentLogEvent::CHARGE_CUSTOMER_PROFILE,
                'status' => $response['success'] ? Status::SUCCESS : Status::FAILURE,
                'response_code' => $response['response_code'] ?? null,
                'response_message' => $response['response_message'] ?? null,
                'transaction_code' => $response['success'] ? $response['transaction_code'] : $response['error_code'],
                'transaction_message' => $response['success'] ? $response['transaction_message'] : $response['error_message'],
                'transaction_id' => isset($response['transaction_id']) ? $response['transaction_id'] : 0,
                'amount' => $amount,
            ]);
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
        }

        return $response;
    }
}
