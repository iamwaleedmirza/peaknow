<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Twilio\Rest\Client;

class MessageController extends Controller
{

    public function sendOtp($phone_no, $user_id)
    {
        $account_sid = env("TWILIO_ACCOUNT_SID", "ACdfe0f6ebc00851a776a559c3618fd112");
        $account_token = env("TWILIO_ACCOUNT_TOKEN", "044d990d7401a116ee93f0ff45415df7");
        $from_no = env("TWILIO_PHONE", "+1 251 451 0608");

        $twilio_client = new Client($account_sid, $account_token);
        $otp = $this->generateNumericOTP(6);
        try {
            User::where('id', $user_id)->update(['phone_otp' => $otp]);
            //send otp to user phone number
            $user_message = $otp . " is your otp. Please verify it to proceed further.";
            $twilio_client->messages->create($phone_no, [
                'from' => $from_no,
                'body' => $user_message
            ]);
            return 1;
        } catch (\Exception $e) {
            $e;
        }
    }

    function generateNumericOTP($n)
    {
        $generator = "1357902468";
        $result = "";
        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand() % (strlen($generator))), 1);
        }
        return $result;
    }
    /**
     * Sending verify otp from twilio to phone number
     * @param $phone_no,$user_id
     */
    public function sendVerifyOtp($phone_no, $user_id)
    {
        $account_sid = env("TWILIO_ACCOUNT_SID", "ACdfe0f6ebc00851a776a559c3618fd112");
        $account_verify_sid = env("TWILIO_VERIFY_SID", "VAae9539bcd9a5a7446907e017858c8244");
        $account_token = env("TWILIO_ACCOUNT_TOKEN", "044d990d7401a116ee93f0ff45415df7");
        $twilio_client = new Client($account_sid, $account_token);
        try {
            $twilio_client->verify->v2->services($account_verify_sid)->verifications->create($phone_no, 'sms');
            return true;
        } catch (\Exception $e) {
            return false;
            $e;
        }
    }
    /**
     * Verifying phone number from twilio with otp
     * @param $phone_no,$user_id
     */
    public function verifyOtp($verification_code, $data, $user_id)
    {
        $account_sid = env("TWILIO_ACCOUNT_SID", "ACdfe0f6ebc00851a776a559c3618fd112");
        $account_verify_sid = env("TWILIO_VERIFY_SID", "VAae9539bcd9a5a7446907e017858c8244");
        $account_token = env("TWILIO_ACCOUNT_TOKEN", "044d990d7401a116ee93f0ff45415df7");

        $twilio_client = new Client($account_sid, $account_token);
        $otp = $this->generateNumericOTP(6);
        try {
            //send otp to user phone number
            $user_message = $otp . " is your otp. Please verify it to proceed further.";
            // $verification = $twilio_client->verify->v2->services($account_verify_sid)
            //     ->verificationChecks
            //     ->create($verification_code, array('to' => $data)); 


            $verification = $twilio_client->verify->v2->services($account_verify_sid)
                ->verificationChecks
                ->create(["to" =>$data,"code" => $verification_code]); 
        
            if (isset($verification) && $verification->valid) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
           return false;
           return $e->getMessage();
        }
    }
     /**
     * Sending verify otp from twilio to email address
     * @param $email,$user_id
     */
    public function sendVerifyEmailOtp($email, $user_id)
    {
        $account_sid = env("TWILIO_ACCOUNT_SID", "ACdfe0f6ebc00851a776a559c3618fd112");
        $account_verify_sid = env("TWILIO_VERIFY_SID", "VAae9539bcd9a5a7446907e017858c8244");
        $account_token = env("TWILIO_ACCOUNT_TOKEN", "044d990d7401a116ee93f0ff45415df7");
        $twilio_client = new Client($account_sid, $account_token);
        try {
            $emailData = [
                'account_username' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'year' => now()->format('Y')
            ];
            $twilio_client->verify->v2->services($account_verify_sid)->verifications->create(
                $email, 'email',
                [
                    "channelConfiguration" => [
                        "substitutions" => $emailData
                    ] 
                ]
            );
            return true;
        } catch (\Exception $e) {
            return false;
            $e;
        }
    }
}
