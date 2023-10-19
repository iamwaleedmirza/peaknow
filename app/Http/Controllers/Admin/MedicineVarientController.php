<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicineVarient;
use Auth;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Admin\MedicineVarient\AddEditMedicineVarientRequest;
use App\Models\NewPlan;

class MedicineVarientController extends Controller
{

    public function index(Request $request) {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = MedicineVarient::select('*');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('created_at',date('Y-m-d'));
                }
            }
            $order = $order->orderBy('created_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->editColumn('name', function ($row) use ($permissions) {
                    return '<a href="javascript:void(0)" class="view-order-details edit-modal" data-id="'.$row->id.'">'.$row->name.'</a>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.medicine.variants.edit',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 edit-modal" data-id="'.$row->id.'">
                                        Edit
                                    </a>
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
                ->rawColumns(['action','name'])
                ->make(true);
        }
        $title = 'Medicine Variant List';
        return view('admin.pages.medicine.index', compact('permissions','title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function EditMedicineVarient(Request $request) {
        $data = MedicineVarient::find($request->id);
        if($data){
            return Response::json(['status'=>true,'info'=>$data], 200);
        } else {
            return Response::json(['status'=>false,'message'=>'Medicine Variant not found'], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddEditMedicineVarientRequest $request) {
        try {
            
            $update = MedicineVarient::where('id',$request->id)->update(['name'=>$request->name]);
            return response()->json(['status' => true,'message' => 'Medicine variant updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => 'Something went wrong','errorMessage' => $th->getMessage()]);
        }
    }

    public function getMedicine(Request $request) {
        $data = NewPlan::where('product_id',$request->product_id)->where('plan_type_id',$request->plan_id)->groupBy('medicine_varient_id')->get()->pluck('medicine_varient_id')->toArray();
        $data = MedicineVarient::whereIn('id',$data)->get();
        if($data){
            return Response::json(['status'=>true,'info'=>$data], 200);
        } else {
            return Response::json(['status'=>false,'message'=>'Medicine Variant not found'], 200);
        }
    }
}
