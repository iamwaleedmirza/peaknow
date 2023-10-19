<?php

namespace App\Http\Controllers\User;

use App\Models\{Plans,NewPlan,Order};
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class PlansController extends Controller
{
    public function checkPlan($id,$qty) {
        $qty = base64_decode($qty);
        if($qty>0 && $id){
            $plan_details = Controller::PlanDetails($id,$qty);
            if($plan_details['status']==1){
                session()->put('selected_plan_id', $id);
                session()->put('selected_plan_qty', $qty);
                if (auth()->user()) {
                    if (Session::get('change-plan') == 1) {
                        return redirect()->route('medical-questions');
                    } else{
                        if (auth()->user()->phone_verified == 0 || auth()->user()->email_verified == 0) {
                            return redirect()->route('account-home');
                        }
                        return redirect()->route('medical-questions');
                    }
                } else {
                    return redirect()->route('home');
                }
            } else {
                return redirect()->to(env('WP_URL'));
            }
        } else {
            return redirect()->to(env('WP_URL'));
        }
    }

    public function ChangePlan($id,$qty) {
        if (auth()->user()) {
            $pqty = base64_decode($qty);
            $order = Order::where('user_id', Auth::user()->id)->where('is_active', 1)->where('is_changed', 0)->orderBy('created_at', 'DESC')->first();
            if($order && (($order->plan_id==$id && $order->product_quantity!=$pqty) || $order->plan_id!=$id)) {
                $qty = base64_decode($qty);
                if($qty>0 && $id){
                    $plan_details = Controller::PlanDetails($id,$qty);
                    if($plan_details['status']==1){
                        session()->put('selected_plan_id', $id);
                        session()->put('selected_plan_qty', $qty);
                        session()->put('change-plan', 1);
                        return redirect()->route('medical-questions');
                    } else {
                        return redirect()->route('home');
                    }
                } else {
                    return redirect()->route('home');
                }
            } else {
                return redirect()->route('home');
            }
        } else {
            return redirect()->route('home');
        }
    }
}
