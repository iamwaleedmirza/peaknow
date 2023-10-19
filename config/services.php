<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'user/login/google/callback',
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('APP_URL').'user/login/facebook/callback',
    ],

    'authorize' => [
        'mode' => env('AUTHORIZE_MODE'),
        'merchant_login_id' => env('MERCHANT_LOGIN_ID'),
        'merchant_transaction_key' => env('MERCHANT_TRANSACTION_KEY'),
        'form_action' => env('AUTHORIZE_FORM_ACTION', 'https://accept.authorize.net/payment/payment'),
        'customer_manage_url' => env('AUTHORIZE_CUSTOMER_MANAGE_URL', 'https://accept.authorize.net/customer/manage'),
        'edit_payment_profile_url' => env('AUTHORIZE_EDIT_PAYMENT_PROFILE_URL', 'https://accept.authorize.net/customer/editPayment'),
    ],

    "beluga" => [
        'key' => env('BELUGA_API_KEY'),
        'base_url' => env('BELUGA_BASE_URL'),
        'cancel_email' => env('BELUGA_CANCEL_EMAIL', 'csadmin@support.belugahealth.com'),
    ],

    'for_beluga' => [
        'access_key' => env('BELUGA_ACCESS_KEY'),
    ],

];
