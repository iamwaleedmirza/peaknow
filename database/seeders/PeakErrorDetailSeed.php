<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PeaksErrorDetail;

class PeakErrorDetailSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PeaksErrorDetail::truncate();
        $errors = [
            [
                'error_type' => 'SmartDoctors',
                'error_code' => '1001',
                'error_description' => 'Error occurs when request is failing to get the telemedicine cost from the Smartdoctors.'
            ],
            [
                'error_type' => 'SmartDoctors',
                'error_code' => '1002',
                'error_description' => 'Error occurs when request is failing to cancel the order on the Smartdoctors.'
            ],
            [
                'error_type' => 'SmartDoctors',
                'error_code' => '1003',
                'error_description' => "Error occurs when request is failing to update the order's shipping address on the Smartdoctors."
            ],
            [
                'error_type' => 'Authorize',
                'error_code' => '2001',
                'error_description' => 'Error occurs when payment is failed on Authorize.net'
            ],
            [
                'error_type' => 'Authorize',
                'error_code' => '2002',
                'error_description' => 'Error occurs when request is failing to pause the subscription on the Peakscurative and while cancelling the subscription on Authorize.net'
            ],
            [
                'error_type' => 'Authorize',
                'error_code' => '2003',
                'error_description' => 'Error occurs when request is failing to resume the subscription on the Peakscurative and while creating the subscription on Authorize.net'
            ],
            [
                'error_type' => 'Authorize',
                'error_code' => '2004',
                'error_description' => 'Error occurs when request is failing to cancel the subscription on Authorize.net'
            ],
            [
                'error_type' => 'LibertyAPI',
                'error_code' => '3001',
                'error_description' => "Error occurs when request is failing to update the user's phone number on the Liberty"
            ],
            [
                'error_type' => 'LibertyAPI',
                'error_code' => '3002',
                'error_description' => "Error occurs when request is failing to update the order's shipping address on the Liberty"
            ],
            [
                'error_type' => 'LibertyAPI',
                'error_code' => '3003',
                'error_description' => 'Error occurs when refill creation request is failing on the Liberty'
            ],
            [
                'error_type' => 'TwilioAPI',
                'error_code' => '4001',
                'error_description' => 'Error occurs when phone number otp request is failing on the Twilio'
            ],
            [
                'error_type' => 'TwilioAPI',
                'error_code' => '4002',
                'error_description' => 'Error occurs when email address otp request is failing on the Twilio'
            ]
        ];
        foreach ($errors as $error) {
            PeaksErrorDetail::create($error);
        }
    }
}
