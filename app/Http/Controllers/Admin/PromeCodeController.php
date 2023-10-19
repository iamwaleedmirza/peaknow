<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plans;
use Illuminate\Http\Request;
use App\Models\{PeaksPromoCode,Product,PlanDetail,MedicineVarient,NewPlan};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Auth;

class PromeCodeController extends Controller
{
    public function getPromoCode(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()){
            $promoCodes = PeaksPromoCode::with('product','plan_type','medicine_variant');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $promoCodes = $promoCodes->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }

            $promoCodes = $promoCodes->orderBy('created_at','DESC')->get();
            return Datatables::of($promoCodes)
                ->addIndexColumn()
                ->addColumn('promocode', function ($row) use ($permissions) {
                    if(in_array('admin.post.promo.code-update-form',$permissions) || Auth::user()->u_type=='superadmin') {
                        return '<a href="javascript:void(0)" data-id="'.$row->id.'" class="menu-link px-3 updatePromoCode">'.$row->promo_name.'</a>';
                    } else {
                        return '<p class="text-dark text-hover-primary">'.$row->promo_name.'</p>';
                    }
                })
                ->editColumn('plan_name', function ($row) {
                    if($row->promo_type==0){
                        return '-';
                    } else {
                        if($row->promo_type==1){
                            return '<span>'.$row->product->name.'</span></br><span>'.$row->plan_type->name.'</span></br><span>'.$row->medicine_variant->name.'</span></br>';
                        } else if($row->promo_type==2){
                            return '<span>'.$row->product->name.'</span>';
                        } else if($row->promo_type==3){
                            return '<span>'.$row->plan_type->name.'</span>';
                        } else if($row->promo_type==4){
                            return '<span>'.$row->medicine_variant->name.'</span></br>';
                        }
                        
                    }
                })
                ->addColumn('discount', function ($row) {
                    return '<span>'.$row->promo_value.'%</span>';
                })
                ->addColumn('status', function ($row) {
                    return ($row->promo_status == true) ? '<span class="badge badge-light-success">Active</span>' : '<span class="badge badge-light-danger">Inactive</span>';
                })
                ->editColumn('promo_type', function ($row) {
                    if($row->promo_type==0){
                        return 'All Plans';
                    } else if($row->promo_type==1){
                        return 'For Particular Plan';
                    } else if($row->promo_type==2){
                        return 'For Particular Product';
                    } else if($row->promo_type==3){
                        return 'For Particular Plan';
                    } else if($row->promo_type==4){
                        return 'For Particular Medicine Variant';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i');
                })
                ->editColumn('updated_at', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $delete = route('admin.delete.promo.code.post',[$row->id]);
                    $action = '';
                    if(in_array('admin.post.promo.code-update-form',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<a href="javascript:void(0)" data-id="'.$row->id.'" class="menu-link px-3 updatePromoCode">Update</a>';
                    }
                    if(in_array('admin.delete.promo.code.post',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<a href="javascript:void(0)" id="deletePromoCode" data-id="'.$row->id.'" data-uri="'.$delete.'" class="menu-link px-3 deletePromoCode delete">Delete</a>';
                    }
                    $btn = '<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                '.$action.'
                                </div>
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','promocode','plan_name','discount','status','created_at','updated_at'])
                ->make(true);

        }
        $title = 'Promo Codes';
        $row['product'] = Product::get();
        $row['plan_detail'] = PlanDetail::get();
        $row['medicine'] = MedicineVarient::get();
        return view('admin.pages.promocode.list', compact('permissions','title','row'));
    }
    public function addPromoCode(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'promo_name' => ['required', 'string', 'unique:peaks_promo_codes,promo_name,'.$request->id.',id'],
                'plan_id' => ['required_if:promo_type,==,1'],
                'product_id' => ['required_if:promo_type,==,1'],
                'medicine_variant_id' => ['required_if:promo_type,==,1'],
                'select_product_id' => ['required_if:promo_type,==,2'],
                'select_plan_id' => ['required_if:promo_type,==,3'],
                'select_medicine_variant_id' => ['required_if:promo_type,==,4'],
                'promo_type' => ['required','in:0,1,2,3,4'],
                'promo_value' => ['required', 'string'],
            ]);
            if($validate->fails()){
                return Response::json(['status'=>false,'message'=>$validate->errors()->first()], 200);
            }
            $product_id = null;
            $plan_id = null;
            $medicine_variant_id = null;
            if($request->promo_type==1){
                $product_id = $request->product_id;
                $plan_id = $request->plan_id;
                $medicine_variant_id = $request->medicine_variant_id;
            } else if($request->promo_type==2){
                $product_id = $request->select_product_id;
            } else if($request->promo_type==3){
                $plan_id = $request->select_plan_id;
            } else if($request->promo_type==4){
                $medicine_variant_id = $request->select_medicine_variant_id;
            }
            PeaksPromoCode::create([
                'promo_name' => $request->promo_name,
                'product_id' => $product_id,
                'plan_type_id' => $plan_id,
                'promo_status' => $request->promo_status,
                'medicine_variant_id' => $medicine_variant_id,
                'promo_type' => $request->promo_type,
                'promo_value' => str_replace('%','',$request->promo_value),
            ]);
            return Response::json(['status'=>true,'message'=>'Promo Code Added Successfully.'], 200);
        } catch (\Throwable $e) {
            return Response::json(['status'=>false,'message'=>'Something went wrong.','error'=>$e->getMessage()], 200);
        }
    }
    public function updatePromoCode(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'promo_name' => ['required', 'string', 'unique:peaks_promo_codes,promo_name,'.$request->id.',id'],
                'plan_id' => ['required_if:promo_type,==,1'],
                'product_id' => ['required_if:promo_type,==,1'],
                'medicine_variant_id' => ['required_if:promo_type,==,1'],
                'select_product_id' => ['required_if:promo_type,==,2'],
                'select_plan_id' => ['required_if:promo_type,==,3'],
                'select_medicine_variant_id' => ['required_if:promo_type,==,4'],
                'promo_type' => ['required','in:0,1,2,3,4'],
                'promo_value' => ['required', 'string'],
            ]);
            if($validate->fails()){
                return Response::json(['status'=>false,'message'=>$validate->errors()->first()], 200);
            }
            $product_id = null;
            $plan_id = null;
            $medicine_variant_id = null;
            if($request->promo_type==1){
                $product_id = $request->product_id;
                $plan_id = $request->plan_id;
                $medicine_variant_id = $request->medicine_variant_id;
            } else if($request->promo_type==2){
                $product_id = $request->select_product_id;
            } else if($request->promo_type==3){
                $plan_id = $request->select_plan_id;
            } else if($request->promo_type==4){
                $medicine_variant_id = $request->select_medicine_variant_id;
            }
            PeaksPromoCode::where('id',$request->id)->update([
                'promo_name' => $request->promo_name,
                'product_id' => $product_id,
                'plan_type_id' => $plan_id,
                'promo_status' => $request->promo_status,
                'medicine_variant_id' => $medicine_variant_id,
                'promo_type' => $request->promo_type,
                'promo_value' => str_replace('%','',$request->promo_value),
            ]);
            return Response::json(['status'=>true,'message'=>'Promo Code Updated Successfully.'], 200);
        } catch (\Throwable $e) {
            return Response::json(['status'=>false,'message'=>'Something went wrong.','error'=>$e->getMessage()], 200);
        }
    }
    public function getUpdatePromoCodeModal(PeaksPromoCode $promoCode)
    {
        $data = NewPlan::where('product_id',$promoCode->product_id)->where('plan_type_id',$promoCode->plan_type_id)->groupBy('medicine_varient_id')->get()->pluck('medicine_varient_id')->toArray();
        if($promoCode->promo_type!=4){
            $promoCode->data = MedicineVarient::whereIn('id',$data)->get();
        } else {
            $promoCode->data = MedicineVarient::get();
        }
        return Response::json(['status'=>true,'message'=>'Fetch Successfully.','info'=>$promoCode], 200);
    }
    public function deletePromoCode(Request $request)
    {
        try {
            $promoCode = PeaksPromoCode::where('id',$request->id)->first();
            if($promoCode){
                PeaksPromoCode::where('id',$request->id)->delete();
                return Response::json(['status'=>true,'message'=>'Promo Code Deleted Successfully.'], 200);    
            } else {
                return Response::json(['status'=>false,'message'=>'Invalid Promo Code'], 200);    
            }
        } catch (\Throwable $e) {
            return Response::json(['status'=>false,'message'=>'Something went wrong'], 200);    
        }
        
    }

}
