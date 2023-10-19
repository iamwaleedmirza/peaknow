<?php

namespace App\Http\Controllers\Admin;

use App\Models\{User,Order};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Auth;

class UserController extends Controller
{
    public function __construct() {
      
    }
    public function getCustomers(Request $request)
    {
        $type = $request->type;
        $permissions = Controller::currentPermission();

        if($request->ajax()){
            $customers = User::where('u_type','patient');
            if ($request->type == 'verified_customer') {
                $customers = $customers->where('email_verified',true)->where('phone_verified',true);
            } elseif ($request->type == 'unverified_customer'){
                $customers = $customers->where(function ($query) { $query->where('email_verified',false)->orWhere('phone_verified',false);});
            } elseif ($request->type == 'inactive_customer'){
                $customers = $customers->where('user_state_allowed',false);
            }

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $customers = $customers->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }

            $customers = $customers->orderBy('created_at','DESC')->get();
            return Datatables::of($customers)
                ->addIndexColumn()
                ->addColumn('id', function ($row) {
                    $url = route('admin.customers.view', [$row]);
                    return '<a href="'.$url.'" class="text-dark text-hover-primary ">'.$row->id.'</a>';  
                })
                ->addColumn('name', function ($row) {
                    $url = route('admin.customers.view', [$row]);
                    return '<a href="'.$url.'" class="view-order-details ">'.$row->first_name.' '.$row->last_name.'</a>';  
                })
                ->editColumn('email', function ($row) {
                    return '<span class="">'.$row->email.'</span>';
                })
                ->editColumn('liberty_patient_id', function ($row) {
                    $url = route('admin.customers.view', [$row]);
                    return ($row->liberty_patient_id) ? '<a href="'.$url.'" class="view-order-details ">'.$row->liberty_patient_id.'</a>' : '-';
                })
                ->editColumn('phone', function ($row) {
                    return '<span class="">'.$row->phone.'</span>';
                })
                ->editColumn('created_at', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $view = route('admin.customers.view', [$row]);
                    $action = '';
                    if(in_array('admin.customers.view',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3"><a href="'.$view.'" class="menu-link px-3">View</a></div>';
                    }
                    if(in_array('admin.customers.change.password',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= ' <div class="menu-item px-3">
                                    <a data-bs-toggle="modal" data-user-id="'.$row->id.'" data-user-email="'.$row->email.'" data-bs-target="#customer_reset_modal" class="menu-link px-3 customer_reset_modalbtn">Reset Password</a>
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
                                <!--begin::Menu item-->
                                
                                '.$action.'
                                <!--end::Menu item-->
                            </div>';
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->rawColumns(['action','id','name','email','phone','created_at','liberty_patient_id'])
                ->make(true);

        }
        $title = 'Customer List';
        return view('admin.pages.customers.customer-list', compact('permissions','title','type'));
    }
    public function getCustomersDetails(User $customer)
    {   
        $permissions = Controller::currentPermission();
        $next = User::select('id')->where('created_at','<',$customer->created_at)->where('u_type','patient')->limit(1)->orderBy('created_at','desc')->first();
        $previous = User::select('id')->where('created_at','>',$customer->created_at)->where('u_type','patient')->limit(1)->orderBy('created_at','asc')->first();
        if (URL::previous() == route('admin.login')) {
            return redirect()->route('admin.home');
        }
        $title = 'Customer Details';
        return view('admin.pages.customers.customers-detail', compact('customer','permissions','title','next','previous'));
    }
    public function getCustomersData(User $customer)
    {
        $permissions = Controller::currentPermission();
        $next = User::select('id')->where('created_at','<',$customer->created_at)->where('u_type','patient')->limit(1)->orderBy('created_at','desc')->first();
        $previous = User::select('id')->where('created_at','>',$customer->created_at)->where('u_type','patient')->limit(1)->orderBy('created_at','asc')->first();
        return view('admin.pages.customers.customers-data', compact('customer','permissions','next','previous'));
    }
    public function changeCustomerPassword(Request $request)
    {
        $request->validate([
            'user_id' => ['required'],
            'password' => ['required', 'string', 'min:8' ,'confirmed'],
        ]);
        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return Response::json(array(
            'success' => true,
            'message' => 'Customer Password has been Updated'
    
        ), 200);
    }

    public function changeCustomerAccountStatus(Request $request)
    {
        try {
            $user = User::find($request->id);
            if($user){
                $status = ($request->status==1) ? 0 : 1;
                $user->update(['account_status'=>$status]);
                $msg = ($status==1) ? 'Account activated successfully' : 'Account disabled successfully';
                return Response::json(array('status' => true,'message' => $msg), 200);
            } else {
                return Response::json(array('status' => false,'message' => 'User not found'), 200);
            }    
        } catch (\Throwable $e) {
            return Response::json(array('status' => false,'message' => 'Something went wrong'), 200);
        }
    }

    public function viewMedicalQuestionList($id)
    {
        $title = 'View Medical Questions and Answers';
        $permissions = Controller::currentPermission();
        $order = Order::with('order_question_answer')->where('order_no',$id)->first();
        if($order){
            $quesAns = json_decode($order->order_question_answer->answers,true);
            return view('admin.pages.orders.view-medical-question', compact('order','permissions','title','quesAns'));
        } else {

        }
        
    }
}
