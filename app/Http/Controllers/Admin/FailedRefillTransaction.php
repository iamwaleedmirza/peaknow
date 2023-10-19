<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Models\Logs\PaymentLog;
use Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use App\Services\AuthorizeService;
use App\Models\OrderRefill;
use App\Enums\Log\PaymentLogEvent;
use App\Http\Controllers\Admin\PaymentSuccessController;

class FailedRefillTransaction extends Controller
{
    public function FailedRefillTransactions(Request $request){
        $permissions = Controller::currentPermission();
        if($request->ajax()) {
            $data = PaymentLog::with('order')->where('event_type',PaymentLogEvent::CHARGE_CUSTOMER_PROFILE)->where('status','FAILURE')->whereHas('order',function ($query) {
                        $query->where('status','Prescribed')->where('cancellation_request',0);
                    });
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $data = $data->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1])->orderBy('created_at','desc')->get();
                } else {
                    $data = $data->whereDate('created_at',date('Y-m-d'))->orderBy('created_at','desc')->get();    
                }
            } else {
                $data = $data->orderBy('created_at','desc')->groupBy('order_no','payment_for')->get();
            }
            $AllData = $data->map(function ($data) {
                $last_transaction_status = PaymentLog::select('status','updated_at')->where('order_no',$data->order_no)->where('payment_for',$data->payment_for)->where('id','!=',$data->id)->latest()->first();
                if($last_transaction_status){
                    $data->last_transaction_status_code = ($last_transaction_status->status=='SUCCESS') ? 1 : 0;
                    $data->last_transaction_status = ($last_transaction_status->status=='SUCCESS') ? '<div class="badge badge-light-success">Success</div>' : '<div class="badge badge-light-danger">failed</div>';
                    $data->last_transaction_updated_date = \Carbon\Carbon::parse($last_transaction_status->updated_at)->format('m/d/Y : H:i');
                    $data->pystatus = $last_transaction_status->status;
                } else {
                    $data->last_transaction_status_code = 0;
                    $data->last_transaction_status = '-';
                    $data->last_transaction_updated_date = '-';
                    $data->pystatus = 0;
                }
            });
            $array = [];
            foreach($data as $value){
                if($value->pystatus!='SUCCESS'){
                    $array[] = $value;
                }
            }
            return Datatables::of($array)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) use ($permissions) {
                    if(in_array('admin.failed.refill.transaction.details',$permissions) || Auth::user()->u_type=='superadmin') {
                        return '<a href="'.url('/').'/admin/view-failed-refill-details/'.$row->id.'" class="menu-link px-3" target="_blank">PC-'.$row->order_no.'</a>';
                    } else {
                        return '<a href="javascript:void(0)" class="text-primary view-order-details view-order-details-hover">PC-'.$row->order_no.'</a>';
                    }
                    
                })
                ->addColumn('fill_type', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    return '<div class="badge badge-light-info">'.$row->payment_for.'</div>';
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
                    return '<span class="badge badge-light-danger">failed</span>';
                })
                ->addColumn('order_date', function ($row) {
                    return '<span>'.$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->order->total_price;
                })
                ->addColumn('action', function ($row) use($permissions) {
                    $action = '';
                    if(in_array('admin.failed.refill.transaction.details',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action .= '<div class="menu-item px-3">
                                    <a href="'.url('/').'/admin/view-failed-refill-details/'.$row->id.'" class="menu-link px-3" target="_blank">View</a>
                                </div>';
                    }
                    if(in_array('admin.force.payment.for.refill',$permissions) || Auth::user()->u_type=='superadmin' && $row->last_transaction_status_code==0) {
                        $action .= ' <div class="menu-item px-3">
                                    <a href="javascript:void(0)" class="menu-link px-3 force_payment" data-id="'.$row->id.'">
                                        Force Payment
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
                ->rawColumns(['action','order_no','fill_type','patient_name','plan_name','status','order_date','total_price','last_transaction_status'])
                ->make(true);
        }
        $title = 'Failed Refill Transactions';
        return view('admin.pages.failed_refill_transaction.index', compact('permissions','title'));
    }

    public function ViewOrderDetails(Request $request,$id){
        $permissions = Controller::currentPermission();
        $title = 'Failed Refill Transaction Details';
        $order = PaymentLog::with('order.user')->where('event_type',PaymentLogEvent::CHARGE_CUSTOMER_PROFILE)->where('status','FAILURE')->whereHas('order')->where('id',$id)->first();
        if($order){
            $order->refill_logs = PaymentLog::where('order_no',$order->order_no)->where('payment_for',$order->payment_for)->latest()->get();
            return view('admin.pages.failed_refill_transaction.failed-refill-details', compact('order','permissions','title'));
        } else {
            return redirect()->route('admin.failed.refill.transaction');
        }
    }

    public function ForcePaymentForRefill(Request $request){
        $paymentData = PaymentLog::with('order.user')->where('event_type',PaymentLogEvent::CHARGE_CUSTOMER_PROFILE)->where('status','FAILURE')->whereHas('order')->where('id',$request->id)->first();
        if($paymentData){
            $orderRefill = $this->OrderRefillEnabled($paymentData->order->user_id,$paymentData->order_no);
            if ($orderRefill) {
                $refill = $paymentData->payment_for;
                if($refill=='NEW FILL'){
                    $refill_number = 0;
                } else {
                    $refillData = explode(' ',$refill);
                    $refill_number = $refillData[1];
                }
                $checkRefill = OrderRefill::where('order_no',$paymentData->order_no)->where('refill_number',$refill_number)->count();
                if($checkRefill==0){
                    session()->put('refillRequest', true);
                    session()->put('order_id', $paymentData->order->id);
                    session()->put('pay_now', true); // For redirecting to payment page directly

                    $response = (new AuthorizeService())->makeTransaction($paymentData->order->user->customer_profile_id,$paymentData->order->user->payment_profile_id,$paymentData->order, true);
                    
                    if ($response['success']) {
                        $paymentSuccessResponse = array(
                            'order_id' => $paymentData->order->id,
                            'transaction_id' => $response['transaction_id']
                        );
                        $responseLog = (new PaymentSuccessController())->StoreData($paymentSuccessResponse);
                        return $responseLog;
                    } else {
                        return Response::json(['status'=>false,'message' => $response['error_message'],'code' => $response['response_code']], 200);
                    }
                } else {
                    return Response::json(['status'=>false,'message' => 'Refill is already created '], 200);
                }
            } else {
                return Response::json(['status'=>false,'message' => 'Order is not active or might be exhausted'], 200);
            }
        } else {
            return Response::json(['status'=>false,'message' => 'Invalid Order Data.'], 200);
        }
    }

    function OrderRefillEnabled($user_id,$order_no){
        $activeOrder = Order::where('user_id', $user_id)
            ->where('status', 'Prescribed')
            ->where('order_no', $order_no)
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
}
