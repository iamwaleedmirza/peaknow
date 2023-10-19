<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Plans;
use App\Models\OrderRefill;
use App\Models\Logs\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Enums\Log\PaymentLogEvent;
use App\Http\Controllers\Api\SmartDoctorsApiController;

class AdminHomeController extends Controller
{

      /**
     * @param $request
     * @return Admin Dashboard Data
     * this is main function of admin dashboard
     */
    public function index(Request $request)
    {
        $permissions = Controller::currentPermission();
        $totalPendingOrders = Order::with('belugaOrder')->where('status','Pending')->where('is_refunded',false)->where('is_exhausted',false)->where('cancellation_request',0)->count();
        $totalPrescribedOrders = $this->number_format_short($this->getOrdersByStatus('Prescribed'));
        $totalDeclinedOrders = $this->number_format_short($this->getOrdersByStatus('Declined'));
        $totalCancelledOrders = $this->number_format_short($this->getOrdersByStatus('Cancelled'));

        $totalDailyOrders = $this->number_format_short(OrderRefill::whereDate('created_at',date('Y-m-d'))->orderBy('created_at','desc')->get()->count());
        $totalTodayPendingOrders = Order::with('belugaOrder')->where('status','Pending')->where('is_refunded',false)->where('is_exhausted',false)->where('cancellation_request',0)->whereDate('created_at',date('Y-m-d'))->count();
        $totalTodayPrescribedOrders = Order::withCount('OrderRefill')->where('status','Prescribed')->where('is_exhausted',false)->where('cancellation_request',0)->whereDate('created_at',date('Y-m-d'))->count();
        $totalTodayDeclinedOrders = Order::where('status','Declined')->where('is_exhausted',false)->whereDate('created_at',date('Y-m-d'))->count();
        $totalTodayCancelledOrders = Order::where('is_exhausted',false)->where('status','Cancelled')->whereDate('created_at',date('Y-m-d'))->count();
        $getWeeklyOrders = $this->getOrderSales('week');
        $getTotalOrders = $this->getOrderSales('total');
        $getTotalShippingOrders = $this->getOrderSales('total_shipping');

        $getVerifiedCustomers = User::where('u_type','patient')->where('email_verified',true)->where('phone_verified',true)->count(); 
        $getUnVerifiedCustomers = User::where('u_type','patient')->where('email_verified',false)->orWhere('phone_verified',false)->count(); 
        $getInactiveUserStateCustomers = User::where('u_type','patient')->where('user_state_allowed',false)->count(); 

        // if(env('APP_ENV')!='local' && env('APP_ENV')!='staging'){
        //     $sdAPI = new SmartDoctorsApiController();
        //     $response = json_decode($sdAPI->getTotalSmartDoctorsCommission(),true);
        //     if (!$response) {
        //         $telemedConsult = 'failure';
        //     }else{
        //         $telemedConsult = $response['total_smartdoctor_commission'];
        //     }
        // } else {
        //     $telemedConsult = 0;
        // }

        $telemedConsult = Order::where('status', 'Prescribed')->orWhere('status', 'Declined')->sum('telemedicine_consult');
        
        if ($getTotalOrders['total_amount'] == null) {
            $getTotalOrders['total_amount'] = 0;
        }else{
            //$getTotalOrders['total_amount'] = $this->number_format_short($getTotalOrders['total_amount']);
            $getTotalOrders['total_amount'] = $getTotalOrders['total_amount'];
        }
        $getTotalShippingOrders['total_shipping_amount'] = 0;
        // $orderData = array();
        // if($orderData == null){
        //     $orderData['total_amount'][] = [];
        //     $orderData['day'][] = [];
        // }

        //  $getWeeklyOrders = $orderData;

        $total_failed_transactions = $this->failedtransactioncount();
        $expired_order = Order::where('is_exhausted',true)->count();
        
        $title = 'Dashboard';
        return view('admin.pages.home', compact('totalPendingOrders', 'totalPrescribedOrders', 'totalDeclinedOrders', 'totalCancelledOrders','totalDailyOrders', 'totalTodayPendingOrders', 'totalTodayPrescribedOrders', 'totalTodayDeclinedOrders', 'totalTodayCancelledOrders', 'getWeeklyOrders', 'getTotalOrders', 'telemedConsult','getVerifiedCustomers','getUnVerifiedCustomers','getInactiveUserStateCustomers','getTotalShippingOrders','permissions','title','total_failed_transactions','expired_order'));
    }
    /**
     * @param $status
     * @return order count Data
     * this returns order count by status
     */
    public function getOrdersByStatus($status)
    {
        return $orders = Order::where('status', $status)->count();
    }
    /**
     * @param $status
     * @return order count Data
     * this returns todays order count by status
     */
    public function getTodayOrdersByStatus($status)
    {

        return  Order::where('status', $status)->whereDate('created_at', date('Y-m-d'))->count();
    }

    /**
     * @param $n
     * @return string
     * Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
     */
    public function number_format_short($n)
    {
        if ($n >= 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = 'K+';
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = 'M+';
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = 'B+';
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = 'T+';
        }

        return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
    }
    /**
     * @param $type
     * @return order sales Data
     * this returns order sales by type like total order sales, weekly order sales etc
     */
    public function getOrderSales($type)
    {
        if ($type == 'total') {
            return Order::select(DB::raw('SUM(order_refills.amount) As total_amount'))
                ->join('order_refills', 'orders.order_no', '=', 'order_refills.order_no')
                ->where('status', 'Prescribed')
                ->get()->first()
                ->toArray();
        }

        if ($type == 'week') {
            //config('database.connections.mysql.strict', false);
            $order = Order::select(
                DB::raw("(SUM(order_refills.amount)) as total_amount"),
                DB::raw("DAYNAME(order_refills.updated_at) as day"),
                DB::raw("DATE(order_refills.updated_at) as date")
            )
            ->join('order_refills', 'orders.order_no', '=', 'order_refills.order_no')
                ->where('status', 'Prescribed')
                ->whereBetween(
                    'order_refills.updated_at',
                    [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
                )
                ->groupBy(['day','date'])
                ->get()
                ->toArray();
            usort($order, [$this,'date_sort']);
            $orderData = array();
            foreach ($order as $key => $value) {
                $orderData['total_amount'][] = $value['total_amount'];
                $orderData['day'][] = $value['day'];
            }
            if ($orderData == null) {
                $orderData['total_amount'][] = [];
                $orderData['day'][] = [];
            }
            //config('database.connections.mysql.strict', true);
            return $orderData;
        }
        if ($type == 'month') {
            $order = Order::select( 
                DB::raw("(SUM(order_refills.amount)) as total_amount"),
                DB::raw("DATE(order_refills.updated_at) as day"),
                DB::raw("DATE(order_refills.updated_at) as date")
            )
            ->join('order_refills', 'orders.order_no', '=', 'order_refills.order_no')
                ->where('status', 'Prescribed')
                ->whereMonth('order_refills.updated_at', date('m'))
                ->groupBy(['day','date'])
                ->get()
                ->toArray();
                usort($order, [$this,'date_sort']);
                $orderData = array();
                foreach ($order as $key => $value) {
                    $orderData['total_amount'][] = $value['total_amount'];
                    $orderData['day'][] = $value['day'];
                }
                if ($orderData == null) {
                    $orderData['total_amount'][] = [];
                    $orderData['day'][] = [];
                }
                return response()->json($orderData);
        }
        if ($type == 'year') {
            $order = Order::select( 
                DB::raw("(SUM(order_refills.amount)) as total_amount"),
                DB::raw("MONTHNAME(order_refills.updated_at) as day")
                
                )
                ->join('order_refills', 'orders.order_no', '=', 'order_refills.order_no')
                ->where('status', 'Prescribed')
                ->whereYear('order_refills.updated_at', date('Y'))
                ->groupBy(['day'])
                ->get()
                ->toArray();
                usort($order, [$this,"compare_months"]);
                $orderData = array();
                foreach ($order as $key => $value) {
                    $orderData['total_amount'][] = $value['total_amount'];
                    $orderData['day'][] = $value['day'];
                }
                if ($orderData == null) {
                    $orderData['total_amount'][] = [];
                    $orderData['day'][] = [];
                }
                return response()->json($orderData);
        }
    }
    function date_sort($a, $b) {
        return strtotime($a['date']) <=> strtotime($b['date']);
    }
    function compare_months($a, $b) {
        $monthA = date_parse($a['day']);
        $monthB = date_parse($b['day']);
    
        return $monthA["month"] - $monthB["month"];
    }

    public function failedtransactioncount(){
        $data = PaymentLog::with('order')->where('event_type',PaymentLogEvent::CHARGE_CUSTOMER_PROFILE)->where('status','FAILURE')->whereHas('order')->groupBy('order_no','payment_for')->get();
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
        return count($array);
    }
}
