<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product,PlanDetail,NewPlan,MedicineVarient};

class PlanController extends Controller
{
    public function FetchPlan(Request $request){
        try {
            $array = [];
            $product = Product::select('id','name')->get();
            foreach ($product as $prod) {
                $data['product'] = $prod;
                $plan_type = PlanDetail::select('id','name','subscription_type')->orderBy('subscription_type','desc')->get();
                $plans = [];
                foreach ($plan_type as $plantp) {
                    $medical_variants = NewPlan::where('product_id',$prod->id)->where('plan_type_id',$plantp->id)->groupBy('medicine_varient_id')->get()->pluck('medicine_varient_id')->toArray();
                    $medicines = [];
                    $medical_variant = MedicineVarient::select('id','name')->whereIn('id',$medical_variants)->orderBy('name','asc')->get();
                    foreach ($medical_variant as $medical) {
                        $medicines[] = $medical;
                    }

                    $NewPlan = [];
                    foreach ($medicines as $medic) {
                        $rev_plan = $medic;
                        $plan_details = NewPlan::select('id', 'product_id', 'plan_type_id', 'medicine_varient_id', 'plan_title', 'plan_image','strength', 'category_plan1', 'category_plan2')->where('product_id',$prod->id)->where('plan_type_id',$plantp->id)->where('medicine_varient_id',$medic->id)->where('is_active',1)->orderBy('created_at','asc')->get();
                        $AllData = $plan_details->map(function ($plan_details) {
                            $plan_details->category_plan_1 = json_decode($plan_details->category_plan1,true);
                            $plan_details->category_plan_2 = json_decode($plan_details->category_plan2,true);
                            $plan_details->plan_image = getImage($plan_details->plan_image);
                            unset($plan_details->category_plan1,$plan_details->category_plan2);
                        });
                        $rev_plan['plan_details'] = $plan_details;
                        $NewPlan[] = $rev_plan;
                    }
                    $plantp->medicine  = $NewPlan;
                    $plans[] = $plantp;
                    $data['product']['plan'] = $plans;

                }
                $array[] = $data;
            }
            if(count($array)){
                $url = url('/wp-plan').'/{plan_id}/{qty}';
                $upgrade_plan_url = url('/wp-change-plan').'/{plan_id}/{qty}';
                return response()->json(['status' => true,'message' => 'fetch Plans.','info' => $array,'plan_redirect_url' => $url,'change_plan_redirect_url' => $upgrade_plan_url], 200);
            } else {
                return response()->json(['status' => false,'message' => 'No plans available.'], 200);
            }
        } catch (\Throwable $e) {
            return response()->json(['status' => false,'message' => 'Something went wrong.','errorMessage'=>$e->getMessage()], 200);
        }
    }
}
