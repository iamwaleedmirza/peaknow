<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderRefill;
use DataTables;
use App\Models\User;
use Auth;
use App\Models\Logs\SubscriptionLogs;
use App\Http\Controllers\Email\EmailController;

class SubscriptionController extends Controller
{

    public function getSubscriptionsByStatus($status)
    {
        $permissions = Controller::currentPermission();
        $subscriptions = User::all();
        $title = 'Subscription List';
        return view('admin.pages.subscriptions_list', compact('subscriptions', 'status','permissions','title'));
    }
    
    public function activeSubscriptions(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $subscriptions = Order::whereHas('user')->where('is_subscription', true)->join('order_subscriptions as sub', 'sub.order_no', '=', 'orders.order_no')->where('is_active', true)->where('is_exhausted', false)->where('sub.is_paused', false);

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $subscriptions = $subscriptions->whereDate('orders.created_at','>=',$dateArray[0])->whereDate('orders.created_at','<=',$dateArray[1]);
                } else {
                    $subscriptions = $subscriptions->whereDate('orders.created_at',date('Y-m-d'));
                }
            }
            $subscriptions =  $subscriptions->latest()->get(['orders.*', 'sub.is_paused']);
            return Datatables::of($subscriptions)
                ->addIndexColumn()
                ->editColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('patient_email', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'"
                                            class="text-dark text-hover-primary">'.$row->user->email.'</a>
                                    </div>
                                </div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_exhausted){
                        $status = '<span class="badge badge-light-danger">Inactive</span>';
                    }
                    elseif($row->is_paused){
                        $status = '<span class="badge badge-light-warning">Paused</span>';
                    }
                    elseif($row->is_active){
                        $status = '<span class="badge badge-light-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $action = '';
                    if(in_array('admin.view.orders',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.subscription.pause',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3"><a href="javascript:void(0)" data-id="'.$row->order_no.'" class="menu-link px-3 resume-subscription"> Pause Subscription </a></div>';
                    }
                    if(in_array('admin.subscription.cancel.by.admin',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3"><a href="javascript:void(0)" data-id="'.$row->order_no.'" class="menu-link px-3 cancel-subscription delete"> Cancel Subscription </a></div>';
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
                ->rawColumns(['action','order_no','patient_email','patient_name','created_at','status'])
                ->make(true);
        }
        $title = 'Subscribers List';
        return view('admin.pages.subscriptions_list', compact('permissions','title'));
    }

    public function pausedSubscriptions(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $subscriptions = Order::whereHas('user')->where('is_subscription', true)->join('order_subscriptions as sub', 'sub.order_no', '=', 'orders.order_no')->where('is_active', true)->where('is_exhausted', false)->where('sub.is_paused', true);
            
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $subscriptions = $subscriptions->whereDate('orders.created_at','>=',$dateArray[0])->whereDate('orders.created_at','<=',$dateArray[1]);
                } else {
                    $subscriptions = $subscriptions->whereDate('orders.created_at',date('Y-m-d'));
                }
            }
            $subscriptions =  $subscriptions->latest()->get(['orders.*', 'sub.is_paused']);
            return Datatables::of($subscriptions)
                ->addIndexColumn()
                ->editColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('patient_email', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'"
                                            class="text-dark text-hover-primary">'.$row->user->email.'</a>
                                    </div>
                                </div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_exhausted){
                        $status = '<span class="badge badge-light-danger">Inactive</span>';
                    }
                    elseif($row->is_paused){
                        $status = '<span class="badge badge-light-warning">Paused</span>';
                    }
                    elseif($row->is_active){
                        $status = '<span class="badge badge-light-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $action = '';
                    if(in_array('admin.view.orders',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row]);
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.$view.'" class="menu-link px-3">
                                        View
                                    </a>
                                </div>';
                    }
                    if(in_array('admin.subscription.pause',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3"><a href="javascript:void(0)" data-id="'.$row->order_no.'" class="menu-link px-3 resume-subscription"> Resume Subscription </a></div>';
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
                ->rawColumns(['action','order_no','patient_email','patient_name','created_at','status'])
                ->make(true);
        }
        $title = 'Subscribers List';
        return view('admin.pages.subscriptions_paused_list', compact('permissions','title'));
    }

    public function expiredSubscriptions(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $subscriptions = Order::whereHas('user')->where('is_subscription', true)->join('order_subscriptions as sub', 'sub.order_no', '=', 'orders.order_no')->where('is_exhausted', true);

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $subscriptions = $subscriptions->whereDate('orders.created_at','>=',$dateArray[0])->whereDate('orders.created_at','<=',$dateArray[1]);
                } else {
                    $subscriptions = $subscriptions->whereDate('orders.created_at',date('Y-m-d'));
                }
            }
            $subscriptions =  $subscriptions->latest()->get(['orders.*', 'sub.is_paused']);
            return Datatables::of($subscriptions)
                ->addIndexColumn()
                ->editColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('patient_email', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'"
                                            class="text-dark text-hover-primary">'.$row->user->email.'</a>
                                    </div>
                                </div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_exhausted){
                        $status = '<span class="badge badge-light-danger">Inactive</span>';
                    }
                    elseif($row->is_paused){
                        $status = '<span class="badge badge-light-warning">Paused</span>';
                    }
                    elseif($row->is_active){
                        $status = '<span class="badge badge-light-success">Active</span>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) {
                    $view = route('admin.view.orders', [$row]);
                    $btn = '<div class="btn btn-sm btn-light btn-active-light-primary">
                                <a href="'.$view.'" class="menu-link px-3"> View </a>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','patient_email','patient_name','created_at','status'])
                ->make(true);
        }
        $title = 'Subscribers List';
        return view('admin.pages.subscriptions_expired_list', compact('permissions','title'));
    }

    public function cancelledSubscriptions(Request $request)
    {
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $subscriptions = Order::whereHas('user')->where('is_active', false)->where('is_subscription_cancelled',true);

            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $subscriptions = $subscriptions->whereDate('updated_at','>=',$dateArray[0])->whereDate('updated_at','<=',$dateArray[1]);
                } else {
                    $subscriptions = $subscriptions->whereDate('updated_at',date('Y-m-d'));
                }
            }
            $subscriptions =  $subscriptions->orderBy('updated_at','desc')->get();
            return Datatables::of($subscriptions)
                ->addIndexColumn()
                ->editColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div><a href="'.$url.'" class="text-dark text-hover-primary">'.$row->user->first_name.' '.$row->user->last_name.'</a></div></div>';
                })
                ->addColumn('patient_email', function ($row) {
                    $url =  route('admin.customers.view', [$row->user->id]);
                    return '<div class="d-flex align-items-center"><div>
                                        <a href="'.$url.'"
                                            class="text-dark text-hover-primary">'.$row->user->email.'</a>
                                    </div>
                                </div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->product_name;
                })
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-light-danger">Cancelled</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return '<span>'.\Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i').'</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.view.cancelled.subscription.details',$permissions) || Auth::user()->u_type=='superadmin') {
                        $view = route('admin.view.orders', [$row]);
                        $action .= '<div class="btn btn-sm btn-light btn-active-light-primary">
                                <a href="javascript:void(0)" class="menu-link px-3 view_subscriber" data-id="'.$row->id.'"> View </a>
                            </div>';
                    }
                    return ($action!='') ? $action : '-';
                })
                ->rawColumns(['action','order_no','patient_email','patient_name','created_at','status'])
                ->make(true);
        }
        $title = 'Cancelled Subscriptions List';
        return view('admin.pages.subscriptions_inactive_list', compact('permissions','title'));
    }

    public function viewcancelSubscription(Request $request)
    {
        $order_no = $request->id;
        $order = Order::where('id', $order_no)
            ->first();

        if (empty($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found!',
            ]);
        }

        $url =  route('admin.view.orders', [$order]);
        $order->order_no = '<a href="'.$url.'" target="_blank" class="text-dark text-hover-primary">PC-'.$order->order_no.'</a>';
        $order->patient_name = $order->user->first_name.' '.$order->user->last_name;
        $order->patient_email = $order->user->email;
        $order->date = \Carbon\Carbon::parse($order->updated_at)->format('m/d/Y : H:i');
        return response()->json([
            'status' => true,
            'info' => $order,
        ]);
    }

    public function PauseSubscriptionResume(Request $request)
    {
        $order_no = $request->id;
        $order = Order::where('order_no', $order_no)
            ->where('is_active', true)
            ->where('is_subscription', true)
            ->first();

        if (empty($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found!',
            ]);
        }

        if (empty($order->subscription)) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription not found!',
            ]);
        }
        
        $order->subscription->update([
            'is_paused' => false,
            'username' => Auth::user()->first_name.' '.Auth::user()->last_name.' ('.ucwords(Auth::user()->u_type).')',
            'updated_by' => 'ADMIN'
        ]);

        SubscriptionLogs::store($order->order_no,'RESUMED', 'ADMIN');

        $resumed_date = date('F j, Y');
        $emailManager = new EmailController();
        $emailManager->sendSubscriptionResumedMail($order, $paused_date);

        return response()->json([
            'status' => true,
            'message' => 'Subscription has been resumed successfully.',
        ]);
    }

    public function PauseSubscription(Request $request)
    {
        $order_no = $request->id;
        $order = Order::where('order_no', $order_no)
            ->where('is_active', true)
            ->where('is_subscription', true)
            ->first();

        if (empty($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found!',
            ]);
        }

        if (empty($order->subscription)) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription not found!',
            ]);
        }
        
        $order->subscription->update([
            'is_paused' => true,
            'username' => Auth::user()->first_name.' '.Auth::user()->last_name.' ('.ucwords(Auth::user()->u_type).')',
            'updated_by' => 'ADMIN'
        ]);

        SubscriptionLogs::store($order->order_no,'PAUSED', 'ADMIN');

        $paused_date = date('F j, Y');
        $emailManager = new EmailController();
        $emailManager->sendSubscriptionPausedMail($order, $paused_date);

        return response()->json([
            'status' => true,
            'message' => 'Subscription has been paused successfully.',
        ]);
    }

    public function cancelSubscription(Request $request)
    {
        $order_no = $request->order_id;
        $order = Order::where('order_no', $order_no)
            ->where('is_active', true)
            ->where('is_subscription', true)
            ->first();

        if (empty($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found!',
            ]);
        }

        if (empty($order->subscription)) {
            return response()->json([
                'status' => false,
                'message' => 'Subscription not found!',
            ]);
        }

        $order->update([
            'is_active' => false,
            'is_subscription_cancelled' => true,
            'cancel_reason' => $request->cancel_reason,
        ]);

        SubscriptionLogs::store($order->order_no,'CANCELLED', 'ADMIN');

        $cancel_date = date('F j, Y');
        $emailManager = new EmailController();
        $emailManager->sendSubscriptionCancelledMail($order, $cancel_date);

        return response()->json([
            'status' => true,
            'message' => 'Subscription has been cancelled successfully.',
        ]);
    }

}
