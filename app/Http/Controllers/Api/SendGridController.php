<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use SendGrid\Mail\Mail;

class SendGridController extends Controller
{
//    private $headers;
//
//    public function __construct()
//    {
//        $this->headers = [
//            'Authorization' => 'Bearer ' . env('SENDGRID_API_KEY'),
//        ];
//    }

    public static function sendMail($to, $templateID, $dynamicData, $from = 'support@peakscurative.com')
    {
        $email = new Mail();

        try {
            $email->setFrom($from, 'PeaksCurative');
            $email->addTo($to);
            $email->setReplyTo('support@peakscurative.com');
            $email->setTemplateId($templateID);
            $email->addDynamicTemplateDatas($dynamicData);

            $sendGrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

            return $sendGrid->send($email);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

//    public function sendMail($sendTo, $templateID, $dynamicData)
//    {
//        $postData = [
//            "from" => [
//                "name" => "PeaksCurative",
//                "email" => 'welcome@peakscurative.com',
//            ],
//            "personalizations" => [
//                [
//                    "to" => [["email" => $sendTo]],
//                    "reply_to" => 'support@peakscurative.com',
//                    "dynamic_template_data" => $dynamicData
//                ]
//            ],
//            "template_id" => $templateID
//        ];
//
//        return Http::withHeaders($this->headers)
//            ->acceptJson()
//            ->post('https://api.sendgrid.com/v3/mail/send', $postData);
//    }
}
