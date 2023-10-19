<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{PlanDetail,Product,MedicineVarient,NewPlan};
use DataTables;
use Illuminate\Http\Request;
use App\Http\Controllers\Utils\FileUploadController;
use Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Admin\SubscriptionPlan\AddEditPlansRequest;

class PlanController extends Controller
{

    public function index(Request $request) {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = NewPlan::with('product','plan_type','medicine_variant');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('created_at',date('Y-m-d'));
                }
            }
            $order = $order->orderBy('created_at','desc')->get();
            // dd($order);
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('product_name', function ($row) use ($permissions) {
                    return '<a href="/admin/plan/edit/'.$row->id.'" class="view-order-details">'.$row->product->name.'</a>';
                })
                ->addColumn('plan_name', function ($row) use ($permissions) {
                    return '<a href="/admin/plan/edit/'.$row->id.'" class="view-order-details">'.$row->plan_type->name.'</a>';
                })
                ->editColumn('plan_image', function ($row) {
                    return '<img src="'.getImage($row->plan_image).'" class="img-fluid plan-image-table" />';
                })
                ->editColumn('strength', function ($row) {
                    return $row->strength.'MG';
                })
                ->editColumn('is_active', function ($row) {
                    if ($row->is_active == 0){
                        $status = '<span class="badge badge-light-danger">Inactive</span>';
                    } else {
                        $status = '<span class="badge badge-light-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('subscription_type', function ($row) {
                    return ($row->plan_type->subscription_type==0) ? 'One Time' : 'Monthly Subscription';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.plan.edit',$permissions) || Auth::user()->u_type=='superadmin') {
                        $edit = route('admin.plan.edit', [$row]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$edit.'" class="menu-link px-3">
                                        Edit
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.delete.plan',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 delete" data-id="'.$row->id.'">Delete</a>
                                </div>';
                    }
                    if($action){
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
                                '.$action.'
                            </div>';
                        return $btn;
                    } else {
                        return '-';
                    }
                })
                ->rawColumns(['action','plan_image','subscription_type','product_name','plan_name','medicine_name','plan_title','is_active'])
                ->make(true);
        }
        $title = 'Plan List';
        return view('admin.pages.plan.index', compact('permissions','title'));
    }

    public function create(Request $request) {
        $permissions = Controller::currentPermission();
        $row['product'] = Product::get();
        $row['plan'] = PlanDetail::get();
        $row['medicine'] = MedicineVarient::get();
        $title = 'Add Plan';
        return view('admin.pages.plan.create', compact('permissions','title','row'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditPlan($id) {
        $permissions = Controller::currentPermission();
        $row['product'] = Product::get();
        $row['plan'] = PlanDetail::get();
        $row['medicine'] = MedicineVarient::get();
        $title = 'Edit Plan';
        $data = NewPlan::where('id',$id)->first();
        if($data){
            $data->category_plan1 = json_decode($data->category_plan1,true);
            $data->category_plan2 = json_decode($data->category_plan2,true);
            $row['data'] = $data;
            return view('admin.pages.plan.create', compact('permissions','title','row'));
        } else {
            return redirect()->route('admin.plan.list');
        }
        return view('admin.pages.plan.create', compact('permissions','title','row'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddEditPlansRequest $request) {
        try {
            
            $data['product_id'] = $request->product;
            $data['plan_type_id'] = $request->plan;
            $data['medicine_varient_id'] = $request->medicine;
            $data['plan_title'] = $request->plan_title;
            $data['strength'] = $request->strength;
            $data['is_active'] = $request->is_active;
            $data['product_ndc'] = $request->product_ndc;
            $data['product_ndc_2'] = (@$request->product_ndc_2) ? $request->product_ndc_2 : '';
            $data['category_plan1'] = json_encode(array(
                'quantity' => $request->quantity1,
                'price' => $request->price1,
                'shipping_cost' => ($request->sh1>0) ? $request->sh1 : 0,
                'discount' => ($request->discount1) ? $request->discount1 : 0,
                'total' => $request->total1,
            ));
            $data['category_plan2'] = json_encode(array(
                'quantity' => $request->quantity2,
                'price' => $request->price2,
                'shipping_cost' => ($request->sh2>0) ? $request->sh2 : 0,
                'discount' => ($request->discount2) ? $request->discount2 : 0,
                'total' => $request->total2,
            ));
            
            if ($request->has('plan_image')) {
                $document = new FileUploadController();
                $name = 'plan_image' . $request['id'] . '_' . uniqid() . '.' . $request->file('plan_image')->extension();
                $data['plan_image'] = $document->upload($request, $name, 'plan_image', 'plan');
            }
            
            if($request->id==''){
                NewPlan::create($data);
                return response()->json(['status' => true,'message' => 'Plan added successfully']);
            } else {
                NewPlan::where('id',$request->id)->update($data);
                return response()->json(['status' => true,'message' => 'Plan updated successfully']);
            }

        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }


    public function DeletePlan(Request $request) {
        $data = NewPlan::where('id',$request->id)->first();
        if($data){
            NewPlan::where('id',$request->id)->delete();
            return response()->json(['status' => true,'message' => 'Plan deleted successfully']);
        } else {
            return response()->json(['status' => false,'message' => 'Something went wrong']);
        }
    }
}
