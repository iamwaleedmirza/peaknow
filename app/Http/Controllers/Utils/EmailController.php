<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use Mail;

class EmailController extends Controller
{
    public function SendContactForm($data)
    {

        $from = env('MAIL_FROM', 'support@peakscurative.com');
        $name = env('MAIL_FROM_NAME', 'Peaks Curative');
        $to_user = $data['email'];
        $to_peaks = "contact@peakscurative.com";
        try {
            Mail::send('emails.contactform_to_user', ['data' => $data], function ($message) use ($from, $name, $to_user) {
                $message->from($from, $name);
                $message->subject('Thanks for letting us know we’ve peaked your interest!');
                $message->to($to_user);
            });
            Mail::send('emails.contactform_to_peaks', ['data' => $data], function ($message) use ($from, $name, $to_peaks) {
                $message->from($from, $name);
                $message->subject('Contact form response from User');
                $message->to($to_peaks);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function SendPopupFormData($data)
    {
        $from = env('MAIL_FROM','support@peakscurative.com');
        $name = env('MAIL_FROM_NAME','Peaks Curative');
        $to_user   = $data['email'];
        try{
            Mail::send('emails.popup_modal_email',['data'=>$data],function ($message) use($from,$name,$to_user) {
                $message->from($from, $name);
                $message->subject("It's time to reach your peak");
                $message->to($to_user);
            });
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    public function SendVerifyEmail($user,$subject)
    {
        $from = env('MAIL_FROM','support@peakscurative.com');
        $name = env('MAIL_FROM_NAME','Peaks Curative');
        $to_user   = $user['email'];
        try{
            Mail::send('emails.verify_email',['data'=>$user],function ($message) use($from,$name,$to_user,$subject) {
                $message->from($from, $name);
                $message->subject($subject);
                $message->to($to_user);
            });
            return true;
        }catch(\Exception $e){
            return false;
        }
    }
}
