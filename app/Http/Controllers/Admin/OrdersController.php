<?php

namespace App\Http\Controllers\Admin;
use App\Models\Order;
use Barryvdh\DomPDF\PDF;
use App\Models\User;
use App\Models\{OrderRefill,BelugaOrderDetail};
use Illuminate\Http\Request;
use App\Models\OrderShipment;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Services\AuthorizeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\FinanceController;
use Auth;
use App\Http\Controllers\Api\SmartDoctorsApiController;
use DataTables;
use App\Http\Controllers\Admin\PaymentSuccessController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Beluga\BelugaApiController;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Email\EmailController;

class OrdersController extends Controller
{
    public int $shippingCost = 0;
    public function __construct() {
        $setting = GeneralSetting::first();
        $this->shippingCost = $setting->shipping_cost;

    }
    public function dailyOrders(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $daily_orders = OrderRefill::whereHas('order')->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1])->orderBy('created_at','desc')->get();
                } else {
                    $daily_orders = OrderRefill::whereHas('order')->whereDate('created_at',date('Y-m-d'))->orderBy('created_at','desc')->get();    
                }
            } else {
                $daily_orders = OrderRefill::whereHas('order')->whereDate('created_at',date('Y-m-d'))->orderBy('created_at','desc')->get();
            }
            
            return Datatables::of($daily_orders)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('fill_type', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    if ($row->refill_number == 0) {
                        $order = 'New Fill';
                        return '<span class="badge badge-light-success">New Fill</span>';
                    } else {
                        return '<span class="badge badge-light-info">Refill '.$row->refill_number.'</span>';
                    }
                    
                })
                ->addColumn('promocode', function ($row) {
                    if($row->order->is_promo_active == true) {
                        return $row->order->promo_code .'('. $row->order->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->order->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->order->user->first_name.' '.$row->order->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->order->plan_name.'</span></br><span>'.$row->order->product_name.'</span></br><span>'.$row->order->medicine_variant.' ('.$row->order->product_quantity.'x'.$row->order->strength.'mg) </span></br>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->refill_status == 'Cancelled'){
                        $status = '<span class="badge badge-light-danger">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Completed'){
                        $status = '<span class="badge badge-light-success">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Confirmed'){
                        $status = '<span class="badge badge-light-info">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Pending'){
                        $status = '<span class="badge badge-light-warning">'.$row->refill_status.'</span>';
                    }
                    return $status;
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->order->total_price;
                })
                ->addColumn('action', function ($row) {
                    $view = route('admin.view.orders', [$row->order]);
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
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','fill_type','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Daily Orders';
        return view('admin.pages.orders.order-fills-list', compact('permissions','title'));

    }
    public function pendingOrders(Request $request){
        $permissions = Controller::currentPermission();
        $today = @$request->today;
        if($request->ajax()) {
            $order = Order::with('belugaOrder')->where('status','Pending')->where('is_refunded',false)->where('is_exhausted',false)->where('cancellation_request',0);
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('created_at',date('Y-m-d'));
                }
            }
            if ($request->today!='') {
                $order = $order->whereDate('created_at',$request->today);
            }
            $order = $order->orderBy('created_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.view.orders',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.pending.order.cancel',$permissions) || Auth::user()->u_type=='superadmin') {
                        if($row->status=='Pending' && $row->payment_status == 'Paid' && $row->cancellation_request==0){
                            $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 delete cancel_order" data-id="'.$row->order_no.'">
                                        Cancel Order
                                    </a>
                                </div>';
                        }
                    }
                    if(in_array('send.to.beluga',$permissions) || Auth::user()->u_type=='superadmin') {
                        if($row->belugaOrder && $row->belugaOrder->sent_to_beluga==0){
                            $action .= '<div class="menu-item px-3">
                                        <a href="javascript:void(0)" class="menu-link px-3 send-to-beluga" data-id="'.$row->order_no.'">
                                            Send to Beluga
                                        </a>
                                    </div>';
                        }
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
                                '.$action.'
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Pending Orders';
        return view('admin.pages.orders.pending', compact('permissions','title','today'));

    }

    public function prescribedOrders(Request $request){
        $permissions = Controller::currentPermission();
        $today = @$request->today;
        if($request->ajax()) {
            $order = Order::withCount('OrderRefill')->where('status','Prescribed')->where('is_exhausted',false)->where('cancellation_request',0);

            if($request->subscription_type!=''){
                $order = $order->where('is_subscription',$request->subscription_type);
            }

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('prescribed_date','>=',$dateArray[0])->whereDate('prescribed_date','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('prescribed_date',date('Y-m-d'));
                }
            }
            if ($request->today!='') {
                $order = $order->whereDate('created_at',$request->today);
            }
            $order = $order->orderBy('prescribed_date','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('prescribed_date', function ($row) {
                    return '<span>'.$row->prescribed_date ? \Carbon\Carbon::parse($row->prescribed_date)->format('m/d/Y : H:i') : \Carbon\Carbon::parse($row->updated_at)->format('Y-m-d : H:i').'</span>';
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $view = route('admin.view.orders', [$row]);
                    $button = '';

                    if(in_array('admin.request.for.refill',$permissions) || Auth::user()->u_type=='superadmin') {
                        if($row->is_exhausted === 0 && $row->is_active==1){
                            $button .= '<div class="menu-item px-3"><a href="javascript:void(0)" data-id="'.$row->order_no.'" class="menu-link px-3 request_for_refill"> Request for Refill </a></div>';
                        }
                    }
                    // if($row->is_subscription==1){
                    //     if(in_array('admin.subscription.cancel.by.admin',$permissions) || Auth::user()->u_type=='superadmin') {
                    //         $button .= '<div class="menu-item px-3"><a href="javascript:void(0)" data-id="'.$row->order_no.'" class="menu-link px-3 cancel-subscription delete"> Cancel Subscription </a></div>';
                    //     }
                    // }
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
                                    <a href="'.$view.'" class="menu-link px-3"> View </a>
                                </div>
                                
                                '.$button.'
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Prescribed Orders';
        return view('admin.pages.orders.prescribed', compact('permissions','title','today'));
    }


    //View Order Details in admin
    public function viewOrderDetail(Order $order){
        $permissions = Controller::currentPermission();
        if (URL::previous() == route('admin.login')) {
            return redirect()->route('admin.home');
        }
        $order->telemedConsult = 30;
        $order->shippingCost = 20;
        $order->discount = $order->shippingCost + $order->telemedConsult;
        $env = config('app.env');
        if($order->invoice && $env !== 'local'){
            $order->invoiceUrl = $this->getImage($order->invoice);
        }else{
            $order->invoiceUrl = asset('storage/'.$order->invoice);
        }
        $title = 'Order Summary';
        return view('admin.pages.orders.order-view', compact('order','env','permissions','title'));
    }
    //To get Order Data from Ajax in admin
    public function viewOrderData(Order $order)
    {
        $permissions = Controller::currentPermission();
    //     $sdAPI = new SmartDoctorsApiController();
    //     $response = json_decode($sdAPI->getSmartDoctorsCommissionForOrder($order->id),true);
    //    if (!$response) {
    //         $smartdoctor_commission = 0;
    //     }else{
    //         $smartdoctor_commission = $response['smartdoctor_commission'];
    //     }
        $order->telemedConsult = $order->telemedicine_consult;
        $order->shippingCost = $order->shipping_cost;
        $order->discount = $order->shipping_cost + $order->telemedConsult;
        $env = config('app.env');
        if($order->invoice && $env !== 'local'){
            $order->invoiceUrl = $this->getImage($order->invoice);
        }else{
            $order->invoiceUrl = asset('storage/'.$order->invoice);
        }
        $title = 'Order Summary';
        $orderShipment = OrderShipment::where('order_no', $order->order_no)->where('refill_number', 0)->first();
        return view('admin.pages.orders.order-data', compact('order','env','orderShipment','permissions','title'));
    }
        //View Order Refill Details in admin
        public function viewOrderRefillDetail(Order $order,$refill_number){
            $permissions = Controller::currentPermission();
            $title = 'Refill Summary';
            return view('admin.pages.orders.order-refill-view', compact('order','refill_number','permissions','title'));
        }
        //To get Order Refill Data from Ajax in admin
        public function viewOrderRefillData(Order $order,$refill_number)
        {
            $permissions = Controller::currentPermission();
            $orderRefill = $order->orderRefill()->where('refill_number',$refill_number)->first();
            $orderShipment = $orderRefill->refillShipment();
            $title = 'Refill Details';
            return view('admin.pages.orders.order-refill-data', compact('order','orderRefill','orderShipment','permissions','title'));
        }
         //To get Order Refill Tracking Data from Ajax in admin
         public function viewOrderRefillTrackingData(Order $order,$refill_number)
         {
            $permissions = Controller::currentPermission();
             $orderRefill = $order->orderRefill()->where('refill_number',$refill_number)->first();
             $orderShipment = $orderRefill->refillShipment();
             $title = 'Refill Details';
             return view('admin.pages.orders.modals.order-refill-tracking-data', compact('order','orderRefill','orderShipment','permissions','title'));
         }
        //To Post Order Refill Tracking Data from Ajax in admin
         public function OrderRefillTrackingDataPost(Request $request)
         {
            $request->validate([
                'order_no' => ['required', 'numeric'],
                'refill_no' => ['required', 'numeric'],
                'tracking_number' => ['required', 'string'],
            ]);

            OrderShipment::where('order_no', $request->order_no)->where('refill_number', $request->refill_no)->update([
                'tracking_number' => $request->tracking_number
            ]);

            return Response::json(['message' => 'Order tracking number has been updated sucessfully.'], 200);
         }
    //To generate Invoice for order-view in admin side
    public function generateInvoice(Order $order)
    {
        $env = config('app.env');
        if ($order->invoice==null) {
            Session::flash('warning', 'Empty Invoice');
            return redirect()->back()->with(['warning','Empty Invoice']);
    }
        if($order->invoice && $env !== 'local'){
            $order->invoiceUrl = $this->getImage($order->invoice);
            return Redirect::to($order->invoiceUrl);

        }else{
             $order->invoiceUrl = storage_path('app/'.$order->invoice);
             return response()->file($order->invoiceUrl);
        }

        return response()->file($order->invoiceUrl);

    }
    //To generate Invoice for order-view in admin side
    public function generateSubscriptionInvoice($order)
    {
        $env = config('app.env');
        $order = OrderRefill::where('order_no',$order)->first();
        if ($order->invoice==null) {
            Session::flash('warning', 'Empty Invoice');
            return redirect()->back()->with(['warning','Empty Invoice']);
        }
        if($order->invoice && $env !== 'local'){
            $order->invoiceUrl = $this->getImage($order->invoice);
            return Redirect::to($order->invoiceUrl);

        }else{
             $order->invoiceUrl = storage_path('app/'.$order->invoice);
             return response()->file($order->invoiceUrl);
        }



    }
    public function declinedOrders(Request $request){
        $permissions = Controller::currentPermission();
        $today = @$request->today;
        if($request->ajax()) {
            $order = Order::where('status','Declined')->where('is_exhausted',false);
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('updated_at','>=',$dateArray[0])->whereDate('updated_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('updated_at',date('Y-m-d'));
                }
            }
            if ($request->today!='') {
                $order = $order->whereDate('created_at',$request->today);
            }
            $order = $order->orderBy('updated_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div> <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('declined_date', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) {
                    $view = route('admin.view.orders', [$row]);
                    $refund = '';
                    if($row->is_refunded == 0){
                        $refund = '<div class="menu-item px-3">
                            <a href="#" class="menu-link px-3 order_refund_modal_btn" data-bs-toggle="modal"
                                data-bs-target="#declined_order_refund_modal"
                                data-order_id="'.$row->order_no.'"
                                data-order_amount="'.$row->total_price.'">Refund</a>
                            </div>';
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
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>
                                '.$refund.'
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price','declined_date'])
                ->make(true);
        }
        $title = 'Declined Orders';
        return view('admin.pages.orders.declined', compact('permissions','title','today'));
    }

    public function cancelledOrders(Request $request){
        $permissions = Controller::currentPermission();
        $today = @$request->today;
        if($request->ajax()) {
            $order = Order::where('is_exhausted',false)->where('status','Cancelled');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('updated_at','>=',$dateArray[0])->whereDate('updated_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('updated_at',date('Y-m-d'));
                }
            }
            if ($request->today!='') {
                $order = $order->whereDate('created_at',$request->today);
            }
            $order = $order->orderBy('updated_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('cancel_date', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) {
                    $view = route('admin.view.orders', [$row]);
                    $refund = '';
                    if($row->is_refunded == 0 && $row->payment_status=='Paid'){
                        $refund = '<div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#order_refund_modal" onclick="refundOrder('.$row->order_no.')">Refund</a>
                            </div>';
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
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>
                                '.$refund.'
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price','cancel_date'])
                ->make(true);
        }
        $title = 'Cancelled Orders';
        return view('admin.pages.orders.cancelled', compact('permissions','title','today'));
   

    }
    public function expiredOrders(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = Order::where('is_exhausted',true);
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('updated_at','>=',$dateArray[0])->whereDate('updated_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('updated_at',date('Y-m-d'));
                }
            }
            $order = $order->orderBy('updated_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');
                })
                ->addColumn('action', function ($row) {
                    $view = route('admin.view.orders', [$row]);
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
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Expired Orders';
        return view('admin.pages.orders.expired', compact('permissions','title'));
    }

    function getImage($image_path)
    {
        $url = "";
        $env = config('app.env');
        if (!empty($image_path)) {
            if ($env == 'local' && Storage::disk('local')->exists($image_path)) {
                $split_file_name = explode('/', $image_path);
                $url = URL::temporarySignedRoute(
                    'images_route',
                    now()->addMinutes(30),
                    ['folder' => $split_file_name[0], 'filename' => $split_file_name[1]]
                );
            } else if ($env != 'local' && Storage::disk('s3')->exists($image_path)) {
                $url = 'https://assets.peakscurative.com/' . $image_path;
            }
        }
        return $url;
    }

    public function RequestRefill(Request $request){
        $order = Order::withCount('getOrderRefills')->where('order_no',$request->id)->where('is_active',1)->where('is_exhausted',0)->first();
        if($order){
            $is_last_refill_shipped = $order->orderRefill()->orderBy('created_at', 'DESC')->first()->is_shipped;
            if ($is_last_refill_shipped) {
                if($order->get_order_refills_count < 6){
                    session()->put('refillRequest', true);
                    session()->put('order_id', $order->id);
                    session()->put('pay_now', true); // For redirecting to payment page directly

                    $response = (new AuthorizeService())->makeTransaction($order->user->customer_profile_id,$order->user->payment_profile_id,$order, true);
                    
                    if ($response['success']) {
                        $paymentSuccessResponse = array(
                            'order_id' => $order->id,
                            'transaction_id' => $response['transaction_id']
                        );
                        return (new PaymentSuccessController())->StoreData($paymentSuccessResponse);
                    } else {
                        return Response::json(['status'=>false,'message' => $response['error_message'],'code' => $response['response_code']], 200);
                    }
                } else  {
                    return Response::json(['status'=>false,'message' => '6 Refills Filled.'], 200);        
                }
            } else {
                return Response::json(['status'=>false,'message' => 'Previous Refill is not shipped yet. Please ship previous Refill and try again.'], 200);
            }
        } else {
            return Response::json(['status'=>false,'message' => 'Order is not active or might be exhausted.'], 200);
        }
    }

    function OrderRefillEnabled($user_id){
        $activeOrder = Order::where('user_id', $user_id)
            ->where('status', 'Prescribed')
            ->where('is_active', true)
            ->where('is_exhausted', false)
            ->first();

        if ($activeOrder == null || empty($activeOrder->refill_until_date)) {
            return false;
        }

        $lastRefill = $activeOrder->orderRefill()->orderBy('created_at', 'DESC')->first();

        $currentDate = strtotime(date('Y-m-d'));

        $isPrescriptionActive = $currentDate < strtotime($activeOrder->refill_until_date);

        $isRefillsAvailable = $activeOrder->refill_count < 5;

        $isLastRefillShipped = $lastRefill->is_shipped == 1;

        return $isPrescriptionActive && $isRefillsAvailable && $isLastRefillShipped;
    }

    public function unshippedOrders(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = OrderRefill::whereHas('order')->where('is_shipped',0)->where('refill_status','Confirmed');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('updated_at','>=',$dateArray[0])->whereDate('updated_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('updated_at',date('Y-m-d'));
                }
            }
            
            $order = $order->orderBy('updated_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('fill_type', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    if ($row->refill_number == 0) {
                        $order = 'New Fill';
                        return '<span class="badge badge-light-success">New Fill</span>';
                    } else {
                        return '<span class="badge badge-light-info">Refill '.$row->refill_number.'</span>';
                    }
                    
                })
                ->addColumn('promocode', function ($row) {
                    if($row->order->is_promo_active == true) {
                        return $row->order->promo_code .'('. $row->order->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->order->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->order->user->first_name.' '.$row->order->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->order->plan_name.'</span></br><span>'.$row->order->product_name.'</span></br><span>'.$row->order->medicine_variant.' ('.$row->order->product_quantity.'x'.$row->order->strength.'mg) </span></br>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->refill_status == 'Cancelled'){
                        $status = '<span class="badge badge-light-danger">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Completed'){
                        $status = '<span class="badge badge-light-success">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Confirmed'){
                        $status = '<span class="badge badge-light-info">'.$row->refill_status.'</span>';
                    }
                    elseif($row->refill_status == 'Pending'){
                        $status = '<span class="badge badge-light-warning">'.$row->refill_status.'</span>';
                    }
                    return $status;
                })
                ->addColumn('order_date', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i');
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->order->total_price;
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $action = '';
                    if(in_array('admin.view.orders',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row->order]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.update.refill.ship.detail',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 mark_as_shipped_modal" data-id="'.$row->id.'">
                                        Mark as Shipped
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
                    } else {
                        $btn = '-';
                    }
                    return $btn;
                })
                ->rawColumns(['action','order_no','fill_type','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Unshipped Orders';
        return view('admin.pages.orders.un-shipped-order', compact('permissions','title'));

    }

    public function UpdateShipmentDetail(Request $request)
    {
        if($request->order_no){
            $order_id = $request->order_no;
        } else {
            $order_id = $request->order_detail_id;
        }
        $refill = OrderRefill::where('id', $order_id)->where('is_shipped',0)->where('refill_status','!=','Cancelled')->first();

        if (empty($refill)) {
            return response()->json([
                'status' => false,
                'message' => 'Order refill not found!',
            ]);
        }
        $data['order_no'] =$refill->order_no;
        $data['refill_number'] =$refill->refill_number;
        $data['ship_date'] = date('Y-m-d',strtotime($request->ship_date));
        $data['ship_type'] = 'FedEx';

        $order = Order::where('order_no',$refill->order_no)->first();
        $user = $order->user;

        if($request->unship_type==1){
            $data['tracking_number'] =$request->tracking_no;
        }

        OrderRefill::where('id',$order_id)->update(['refill_status'=>'Completed','is_shipped'=>1]);

        OrderShipment::create($data);


        if($request->unship_type==1){
            $data['tracking_number'] =$request->tracking_no;
            if ($refill->refill_number == 0) {
                $this->sendShippingConfirmationMail($order, $user, $request->tracking_no, $data['ship_date']);
            } else {
                $emailManager = new EmailController();
                $emailManager->sendRefillShippingConfirmation($order, $refill, $request->tracking_no, $data['ship_date']);
            }
        } else {
            if ($refill->refill_number == 0) {
                $this->sendShippingManualShippingConfirmationMail($order, $user, $request->tracking_no, $data['ship_date']);
            } else {
                $emailManager = new EmailController();
                $emailManager->sendRefillManualShippingConfirmation($order, $refill, $request->tracking_no, $data['ship_date']);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Shipment status updated successfully',
        ]);
    }

    private function sendShippingConfirmationMail($order_data, $user, $trackingNumber, $shipDate)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();

        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'order_no' => $order->order_no,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'tracking_action_url' => "https://www.fedex.com/fedextrack/?trknbr=${trackingNumber}",
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_date' => \Carbon\Carbon::parse($order->created_at)->format('F j, Y'),
            'shipped_date' => \Carbon\Carbon::parse($shipDate)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_SHIPPING_CONFIRMATION, $emailData, 'order-update@peakscurative.com');
    }

    private function sendShippingManualShippingConfirmationMail($order_data, $user, $trackingNumber, $shipDate)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();

        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'order_no' => $order->order_no,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_date' => \Carbon\Carbon::parse($order->created_at)->format('F j, Y'),
            'shipped_date' => \Carbon\Carbon::parse($shipDate)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_MANUAL_SHIPPING_CONFIRMATION, $emailData, 'order-update@peakscurative.com');
    }

    public function PendingOrderCancelled(Request $request){
        $order = Order::where('order_no', $request->order_id)->where('status','Pending')->where('payment_status','Paid')->where('cancellation_request',0)->first();
        if($order){
            $response = $this->cancelOrderOnSD($order->order_no);
            if ($response) {
                if ($request->cancel_reason === 'others') {
                    $cancelReason = $request->cancel_reason_other;
                } else {
                    $cancelReason = $request->cancel_reason;
                }

                $order->update([
                    'cancellation_request' => true,
                    'is_active' => 0,
                    'is_cancellation_request_by_admin' => 1,
                    'cancellation_request_date' => date('Y-m-d H:i:s'),
                    'cancellation_request_by_admin_name' => Auth::user()->first_name.' '.Auth::user()->last_name
                ]);
                $title = 'Order cancellation request has been sent to Beluga.';
                $message = 'Upon complete cancellation of the order, a full refund will be issued to customer.';
                $this->updateChangedOrderStatus($order);
                return Response::json(['status'=>true,'message'=>$message,'title'=>$title], 200);
            } else {
                return Response::json(['status'=>false,'code'=>Error_1002_Title,'message'=>Error_1002_Description], 200);
            }
        } else {
            return Response::json(['status'=>false,'message' => 'Something went wrong!'], 200);
        }
    }


    public function updateChangedOrderStatus($order): void
    {
        $changedOrder = Order::where('user_id', $order->user_id)
            ->where('is_active', true)
            ->where('is_changed', true)
            ->first();

        if ($changedOrder) {
            $changedOrder->update([
                'is_changed' => false
            ]);
        }
    }

    public function cancelOrderOnSD($orderId)
    {
        try {
            $order = Order::where('order_no', $orderId)->first();

            if (empty($order)) {
                return response()->json([
                    "message" => 'Order not found.'
                ], 404);
            }

            if (!empty($order->belugaOrder->visitId)) {
                $masterId = 'PC-' . $order->order_no;
                $patient_name = $order->user->first_name.' '.$order->user->last_name;
                $emailData = [
                    'patient_name' => $patient_name,
                    'master_id' => $masterId
                ];
                $mailResponse = SendGridController::sendMail(config('services.beluga.cancel_email'), TEMPLATE_ID_CANCEL_ORDER_STATUS_FROM_WEBHOOK, $emailData, 'support@peakscurative.com');
            }

            return response()->json([
                "message" => "Order status updated successfully."
            ], 200);

        } catch (Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
        // $site_url = url('/');
        // $accessToken = array(
        //     'authorization' => env('PEAKS_API_ORDER_TOKEN'),
        //     'site_url' => $site_url
        // );
        // $accessToken = json_encode($accessToken);

        // $postData = ['order_id' => $orderId, 'status' => 'Cancellation Request'];

        // try {
        //     $response = Http::withHeaders([
        //         "Accept" => "application/json",
        //         "Authorization" => $accessToken
        //     ])->post(env('SD_API_URL') . 'api/change-order-status', $postData);

        //     if ($response->status() == 200) {
        //         return true;
        //     } else {
        //         return false;
        //     }
        // } catch (\Exception $e) {
        //     Log::error($e->getMessage());
        //     return false;
        // }
    }

    public function sendToBeluga(Request $request)
    {
        $order_no = $request->id;
        $order = BelugaOrderDetail::where('order_no', $order_no)
            ->where('sent_to_beluga', false)
            ->first();

        if (empty($order)) {
            return Response::json(['status'=>false,'message'=>"Order not found"], 200);
        }
        
        $response = (new BelugaApiController())->createVisit($order_no);

        if (!$response) {
            return Response::json(['status'=>false,'message'=>"Something went wrong"], 200);
        }
        return Response::json(['status'=>true,'message'=>"Order sent successfully"], 200);
    }

    public function BelugaPendingOrders(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = \DB::table('orders')
                ->join('beluga_order_details', 'orders.order_no', '=', 'beluga_order_details.order_no')
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select('orders.*', 'beluga_order_details.order_no','beluga_order_details.sent_to_beluga','users.first_name','users.last_name')
                ->where('orders.status','Pending')
                ->where('beluga_order_details.sent_to_beluga',0);

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $order = $order->whereDate('orders.created_at','>=',$dateArray[0])->whereDate('orders.created_at','<=',$dateArray[1]);
                } else {
                    $order = $order->whereDate('orders.created_at',date('Y-m-d'));
                }
            }
            $order = $order->orderBy('orders.created_at','desc')->get();
            return Datatables::of($order)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->id]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user_id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->first_name.' '.$row->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('send.to.beluga',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                <a href="javascript:void(0)" class="menu-link px-3 send-to-beluga" data-id="'.$row->order_no.'">
                                    Send to Beluga
                                </a>
                            </div>';
                    }
                    if($action!='') {
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
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Beluga Pending Orders';
        return view('admin.pages.orders.beluga-pending-order', compact('permissions','title'));
    }

    public function UpdateLibertyScriptNumber(Request $request){
        try {
            $checkscript = Order::where('script_number',$request->liberty_script_number)->where('id','!=',$request->order_no)->count();
            if($checkscript==0){
                $order = Order::where('id',$request->order_no)->first();
                if($order){
                    $order->update(['script_number'=>$request->liberty_script_number,'is_script_entered_by_admin'=>1]);
                    return Response::json(['status'=>true,'message'=>"Liberty script number updated successfully"], 200);    
                } else {
                    return Response::json(['status'=>false,'message'=>"Invalid order!"], 200);    
                }
            } else {
                return Response::json(['status'=>false,'message'=>"Script number is already exists. Please enter different script number."], 200);    
            }
        } catch (\Throwable $e) {
            return Response::json(['status'=>false,'message'=>"Something went wrong!"], 200);
        }
    }

    public function CancellationOrderPending(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $order = Order::where('cancellation_request',1)->where('status','Pending');
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
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->id]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user_id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->plan_name.'</span></br><span>'.$row->product_name.'</span></br><span>'.$row->medicine_variant.' ('.$row->product_quantity.'x'.$row->strength.'mg) </span></br>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->total_price;
                })
                ->addColumn('promocode', function ($row) {
                    if($row->is_promo_active == true) {
                        return $row->promo_code .'('. $row->promo_discount_percent .' %)';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.view.orders',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>';
                    }
                    if($row->doctor_response!='' && (in_array('admin.mark.as.cancelled.order',$permissions) || Auth::user()->u_type=='superadmin')) {
                        $action .= '<div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 delete mark_as_cancelled" data-id="'.$row->id.'">
                                        Mark as Cancelled
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.refund.order',$permissions) || Auth::user()->u_type=='superadmin') {
                        if($row->is_refunded==0 && $row->payment_status=='Paid'){
                            $action .= '<div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#order_refund_modal" onclick="refundOrder('.$row->order_no.')">Refund</a>
                                </div>';
                        }
                    }
                    if($action!='') {
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
                ->rawColumns(['action','order_no','promocode','patient_name','plan_name','status','order_date','total_price'])
                ->make(true);
        }
        $title = 'Beluga Cancellation Pending Orders';
        return view('admin.pages.orders.cancellation_pending_order', compact('permissions','title'));
    }

    public function MarkAsCancelledOrder(Request $request){
        try {
            $order = Order::where('doctor_response','!=','')->where('id','!=',$request->order_no)->where('status','Pending')->where('cancellation_request',1)->first();
            if($order){
                $order->update(['status'=>'Cancelled','cancellation_request'=>0]);

                $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
                if ($orderRefill) {
                    $orderRefill->update([
                        'refill_status' => 'Cancelled',
                    ]);
                }
                return Response::json(['status'=>true,'message'=>"Order cancelled successfully"], 200);    
            } else {
                return Response::json(['status'=>false,'message'=>"Invalid Order Request."], 200);    
            }
        } catch (\Throwable $e) {
            return Response::json(['status'=>false,'message'=>"Something went wrong!"], 200);
        }
    }
}