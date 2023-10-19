<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Utils\AuthorizeController;
use App\Models\OrderRefundHistory;
use DataTables;

class FinanceController extends Controller
{
   public function refundHistory(Request $request)
   {
      $permissions = Controller::currentPermission();      
      if($request->ajax()) {
            $orders = OrderRefundHistory::with('order')->whereHas('order');
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $orders = $orders->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }
            $orders = $orders->orderBy('created_at','desc')->get();
            
            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    return '<a href="'.$url.'" class="view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('fill_type', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    if ($row->refill_number == 0) {
                        $order = 'New Fill';
                        return '<a href="'.$url.'" class="badge badge-light-success">New Fill</a>';
                    } else {
                        return '<a href="'.$url.'" class="badge badge-light-info">Refill '.$row->refill_number.'</a>';
                    }
                    
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->order->user->id]);
                    return '<div class="d-flex align-items-center"><div> <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->order->user->first_name.' '.$row->order->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return '<span>'.$row->order->plan_name.'</span></br><span>'.$row->order->product_name.'</span></br><span>'.$row->order->medicine_variant.' ('.$row->order->product_quantity.'x'.$row->order->strength.'mg) </span></br>';
                })
                ->editColumn('status', function ($row) {
                    return ($row->status==1) ? '<span class="badge badge-light-success">Success</span>' : '<span class="badge badge-light-danger">Failed</span>';
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->order->total_price;
                })
                ->addColumn('refunded_amount', function ($row) {
                    return $row->order->getRefundTransaction() !== null?'$'.$row->order->getRefundTransaction()->amount:'';
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->order->getRefundTransaction() !== null?$row->order->getRefundTransaction()->transaction_id:'No Transaction Found!';
                })
                ->addColumn('updated_at', function ($row) {
                    return '<span>'.$row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) use ($permissions) {
                    $action = '';
                    if(in_array('admin.view.cancelled.subscription.details',$permissions) || Auth::user()->u_type=='superadmin') {
                        $action = '<div class="btn btn-sm btn-light btn-active-light-primary">
                                <a href="javascript:void(0)" class="menu-link px-3 view_refund_details" data-id="'.$row->id.'"> View </a>
                            </div>';
                    }
                    return ($action!='') ? $action : '-';
                })
                ->rawColumns(['action','order_no','fill_type','patient_name','plan_name','total_price','refunded_amount','transaction_id','updated_at','status'])
                ->make(true);
      }
      $title = 'Refund History';
      return view('admin.pages.refund_history', compact('permissions','title'));
   }

    public function refundHistoryDetails(Request $request)
    {
        $id = $request->id;
        $order  = OrderRefundHistory::with('order')->whereHas('order')->where('id', $id)->first();

        if (empty($order)) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found!',
            ]);
        }

        $url =  route('admin.view.orders', [$order->order]);
        $order->order_no = '<a href="'.$url.'" target="_blank" class="text-dark text-hover-primary">PC-'.$order->order_no.'</a>';
        if ($order->refill_number == 0) {
            $order->refill = '<a href="'.$url.'" target="_blank" class="text-dark text-hover-primary">New Fill</a>';
        } else {
            $order->refill = '<a href="'.$url.'" target="_blank" class="text-dark text-hover-primary">Refill '.$order->refill_number.'</a>';
        }
        if($order->status==1){
            $order->reason = ($order->refund_reason) ? $order->refund_reason : '-';
            $order->date_label = 'Refunded Date';
        } else {
            $order->date_label = 'Failed Date';
            $order->reason = ($order->failure_reason) ? $order->failure_reason : '-';
        }
        $order->status = ($order->status==1) ? '<span class="badge badge-light-success">Success</span>' : '<span class="badge badge-light-danger">Failed</span>';

        $order->refund_amount = ($order->amount) ? '$'.$order->amount : '-';
        $order->total_amount = '$'.$order->order->total_price;
        $order->transaction_id = ($order->transaction_id) ? $order->transaction_id : '-';
        $order->plan_name = $order->order->product_name;
        $order->patient_name = $order->order->user->first_name.' '.$order->order->user->last_name;
        $order->patient_email = $order->order->user->email;
        $order->date = \Carbon\Carbon::parse($order->created_at)->format('m/d/Y : H:i');
        return response()->json([
            'status' => true,
            'info' => $order,
        ]);
    }


   public function refundFailed(Request $request)
   {
        $permissions = Controller::currentPermission();
        $title = 'Refund Failed';
        if($request->ajax()) {
            $orders = OrderRefundHistory::with('order')->whereHas('order')->where('status',0);
            if($request->kt_ecommerce_sales_flatpickr){
                $dateArray = explode(' to ', $request->kt_ecommerce_sales_flatpickr);
                if(count($dateArray)==2){
                    $orders = $orders->whereDate('created_at','>=',$dateArray[0])->whereDate('created_at','<=',$dateArray[1]);
                }
            }
            $orders = $orders->orderBy('created_at','desc')->get();
            
            return Datatables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_no', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    return '<a href="'.$url.'" class="text-dark text-hover-primary view-order-details">PC-'.$row->order_no.'</a>';
                })
                ->addColumn('fill_type', function ($row) {
                    $url =  route('admin.view.orders', [$row->order]);
                    if ($row->refill_number == 0) {
                        $order = 'New Fill';
                        return '<a href="'.$url.'" class="text-dark text-hover-primary">New Fill</a>';
                    } else {
                        return '<a href="'.$url.'" class="text-dark text-hover-primary">Refill '.$row->refill_number.'</a>';
                    }
                    
                })
                ->addColumn('patient_name', function ($row) {
                    $url =  route('admin.customers.view', [$row->order->user->id]);
                    return '<div class="d-flex align-items-center"><div> <a href="'.$url.'" class="text-dark text-hover-primary">'.$row->order->user->first_name.' '.$row->order->user->last_name.'</a></div></div>';
                })
                ->addColumn('plan_name', function ($row) {
                    return $row->order->product_name;
                })
                ->addColumn('total_price', function ($row) {
                    return '$'.$row->order->total_price;
                })
                ->addColumn('refunded_amount', function ($row) {
                    return $row->order->getRefundTransaction() !== null?'$'.$row->order->getRefundTransaction()->amount:'';
                })
                ->addColumn('transaction_id', function ($row) {
                    return $row->order->getRefundTransaction() !== null?$row->order->getRefundTransaction()->transaction_id:'No Transaction Found!';
                })
                ->addColumn('updated_at', function ($row) {
                    return '<span>'.$row->updated_at ? \Carbon\Carbon::parse($row->updated_at)->format('m/d/Y : H:i') : '-'.'</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn btn-sm btn-light btn-active-light-primary">
                                <a href="javascript:void(0)" class="menu-link px-3 view_refund_details" data-id="'.$row->id.'"> View </a>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action','order_no','fill_type','patient_name','plan_name','total_price','refunded_amount','transaction_id','updated_at'])
                ->make(true);
      }
        return view('admin.pages.refund_failed', compact('permissions','title'));
   }
   public function refundOrder(Request $request)
   {
      $order_payment = Order::where('order_no', $request->order_id)->first();
      if ($order_payment->is_subscription == 1) {
         $trans = $order_payment->getLatestTransaction();
         if (trim($trans) == null) {
            return Response::json(['warning', 'Transaction ID not Found!!!'], 400);
         }
         $refTransId = $trans->transaction_id;
      } else {
         $refTransId = $order_payment->transaction_id;
      }
      //Added Check for Order if null
      if (trim($order_payment) == null) {
         return Response::json(['warning', 'Order not Found!!!'], 400);
      }

      if ($order_payment->is_refunded==1) {
         return Response::json(['warning', 'Order amount was already refunded!!!'], 400);
      }

      //Added Check for transition ID if null
      if (trim($refTransId) == null) {
         return Response::json(['warning', 'Transaction ID not Found!!!'], 400);
      }

      if ($request->order_amount_type == 'custom_amount') {
         $amount = $request->order_amount;
      } elseif ($request->order_amount_type == 'partial_amount') {
         $amount = (50 / 100) * $order_payment->total_price;
      } elseif ($request->order_amount_type == 'total_amount') {
         $amount = $order_payment->total_price;
      } else {
         $amount = $order_payment->total_price;
      }

      $order_id = $request->order_id;
      $refillNumber = $order_payment->refill_count;
      $response = (new AuthorizeController)->refundTransaction($refTransId, $amount, $order_id, $refillNumber,0,'Refunded by admin');
      if ($response == 'success') {
         return Response::json(['success', 'Order has been Refunded sucessfully.'], 200);
      } else {
         return Response::json(['warning', 'Refund failed!!!'], 400);
      }
   }

       /**
     * Refund Order function
     */
    public function refundOrderById($order_id,$automate = 0,$reason = null)
    {
        $order_payment = Order::where('order_no', $order_id)->first();
        if ($order_payment->is_subscription == 1) {
            $trans = $order_payment->getLatestTransaction();
            if (trim($trans) == null) {
                Log::debug('Transaction ID not Found!!!');
                return false;
            }
            $refTransId = $trans->transaction_id;
        } else {
            $refTransId = $order_payment->transaction_id;
        }
        //Added Check for Order if null
        if (trim($order_payment) == null) {
            Log::debug('Order not Found!!!');
            return false;
        }
        //Added Check for transition ID if null
        if (trim($refTransId) == null) {
            Log::debug('Transaction ID not Found!!!');
            return false;
        }

        if ($order_payment->is_refunded==1) {
            return false;
        }

        $amount = $order_payment->total_price;

        $refillNumber = $order_payment->refill_count;

        $response = (new AuthorizeController)->refundTransaction($refTransId, $amount, $order_id,$refillNumber,$automate,$reason);
        if ($response == 'success') {
            Log::debug('Order has been Refunded sucessfully.');
        } else {
            Log::debug('Refund failed!!!');
            return false;
        }
    }
}
