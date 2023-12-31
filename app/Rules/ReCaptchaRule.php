<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ReCaptchaRule implements Rule
{
    public function __construct() {
  
    }
  
    public function passes($attribute, $value) {
        $data = array('secret' => env('RECAPTCHAV3_SECRET'),
            'response' => $value);
  
        try {
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, 
                        http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($verify);
  
            return json_decode($response) -> success;
        }
        catch (\Exception $e) {
            return false;
        }
    }
  
    public function message() {
        return 'Captcha verification failed. Please refresh your page and try again.';
    }
}
