<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\PeaksPromoCode;
use App\Models\{Plans,NewPlan};
use Exception;
use Illuminate\Http\Request;
use Validator;

class DiscountController extends Controller
{
    public $shippingCost = 0;

    public function __construct()
    {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;
    }

    public function validatePromoCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'promo_code' => 'required',
                'plan_id' => 'required|exists:plans,id',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Invalid Request.',
                ]);
            }

            $data = $validator->validated();

            $promoCode = PeaksPromoCode::where('promo_name', $data['promo_code'])
                ->where('promo_status', 1)
                ->first();

            if (empty($promoCode)) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Sorry, this promo code is not valid.',
                ]);
            }

            if($promoCode->promo_type==1){
                $checkPlan = NewPlan::where('id',$data['plan_id'])->where('product_id',$promoCode->product_id)->where('plan_type_id',$promoCode->plan_type_id)->where('medicine_varient_id',$promoCode->medicine_variant_id)->count();
                if ($checkPlan<=0) {
                    return response()->json([
                        'code' => 404,
                        'message' => 'Sorry, this promo code is not valid.',
                    ]);
                }
            } 

            if($promoCode->promo_type==2){
                $checkPlan = NewPlan::where('id',$data['plan_id'])->where('product_id',$promoCode->product_id)->count();
                if ($checkPlan<=0) {
                    return response()->json([
                        'code' => 404,
                        'message' => 'Sorry, this promo code is not valid.',
                    ]);
                }
            }

            if($promoCode->promo_type==3){
                $checkPlan = NewPlan::where('id',$data['plan_id'])->where('plan_type_id',$promoCode->plan_type_id)->count();
                if ($checkPlan<=0) {
                    return response()->json([
                        'code' => 404,
                        'message' => 'Sorry, this promo code is not valid.',
                    ]);
                }
            }

            if($promoCode->promo_type==4){
                $checkPlan = NewPlan::where('id',$data['plan_id'])->where('medicine_varient_id',$promoCode->medicine_variant_id)->count();
                if ($checkPlan<=0) {
                    return response()->json([
                        'code' => 404,
                        'message' => 'Sorry, this promo code is not valid.',
                    ]);
                }
            }


            $plan_details = Controller::PlanDetails($data['plan_id'],$data['quantity']);
            if($plan_details['status']==1){
                $plan = $plan_details['info']->plan_details;
                $discountPercentage = $promoCode->promo_value;
                $discountValue = round(($discountPercentage / 100) * $plan->total, 2);
                $productPriceAfterDiscount = $plan->total - $discountValue;
                $totalAfterDiscount = $productPriceAfterDiscount + $this->shippingCost;

                $promoSummary = [
                    'promoCode' => $promoCode->promo_name,
                    'discountPercentage' => $discountPercentage,
                    'discountValue' => sprintf('%0.2f',$discountValue),
                    'productPriceAfterDiscount' => sprintf('%0.2f',$productPriceAfterDiscount),
                    'totalAfterDiscount' => sprintf('%0.2f',$totalAfterDiscount)
                ];

                if (session()->has('promoSummary')) {
                    session()->forget('promoSummary');
                }

                session()->put('promoSummary', $promoSummary);

                return response()->json([
                    'code' => 200,
                    'message' => 'Promo Code applied successfully.',
                    'promoSummary' => $promoSummary,
                ]);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Invalid Plan Details.',
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function removePromo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id' => 'required',
                'quantity' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Invalid Request.',
                ]);
            }

            $data = $validator->validated();

            $plan_details = Controller::PlanDetails($data['plan_id'],$data['quantity']);
            if($plan_details['status']==1){
                $plan = $plan_details['info']->plan_details;
                $discountValue = 0;
                $totalPrice = $plan->total;

                $orderSummary = [
                    'discountValue' => $discountValue,
                    'totalPrice' => $totalPrice,
                ];

                if (session()->has('promoSummary')) {
                    session()->forget('promoSummary');
                }

                return response()->json([
                    'code' => 200,
                    'message' => 'Promo Code removed successfully.',
                    'orderSummary' => $orderSummary,
                ]);
            } else {
                return response()->json([
                    'code' => 404,
                    'message' => 'Invalid Request.',
                ]);
            }

            

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
