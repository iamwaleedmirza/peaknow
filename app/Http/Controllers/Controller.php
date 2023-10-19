<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\{User,NewPlan};
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controller as BaseController;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function currentPermission(){
        $permissionArray = [];
        $userRole = Role::where('id',Auth::user()->role_id)->first();
        if($userRole){
            $userRole->rolePermissions = $userRole->permissions;
            foreach ($userRole->rolePermissions as $value) {
                // print_r($value);exit;
                 $permissionArray[] = $value->name;
            }
        }
        return $permissionArray;
    }

    public static function roleList(){
        $userRole = User::groupBy('u_type')->get()->pluck('u_type')->toArray();
        return $userRole;
    }

    public static function PlanDetails($plan_id,$qty){
        $plan = NewPlan::with('product:id,name','plan_type:id,name,subscription_type','medicine_variant:id,name')->where('id', $plan_id)->first();
        if($plan) {
            $plan_qty_1 = json_decode($plan->category_plan1)->quantity;
            $plan_qty_2 = json_decode($plan->category_plan2)->quantity;
            if($plan_qty_1==$qty || $plan_qty_2==$qty){
                if($plan_qty_1==$qty){
                    $plan->plan_details = json_decode($plan->category_plan1);
                } else {
                    $plan->plan_details = json_decode($plan->category_plan2);
                }
                $status = 1;
            } else {
                $status = 0;
            }
        } else {
            $status = 0;
        }
        return (['status' => $status,'info' => $plan]);
    }
}
