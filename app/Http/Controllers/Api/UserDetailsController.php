<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PaymentController;
use App\Models\Order;
use App\Models\OrderRefundHistory;
use App\Models\QuestionAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserDetailsController extends Controller
{
    public function getUserInfo($order_id)
    {
        try {
            $order = Order::where('order_no', $order_id)->first();

            $user = User::where('id', $order->user_id)
                ->select('first_name', 'last_name', 'email', 'phone', 'gender', 'dob')
                ->first();

            $quesAns = QuestionAnswer::where('id', $order->question_ans_id)->value('answers');
            $quesAns = json_decode($quesAns);
            unset($quesAns->{'_token'});
            $quesAns = (array)$quesAns;

            return response()->json([
                'message' => 'Success! user details fetched.',
                'user' => $user,
                'answers' => $quesAns
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Getting Order Shipping Detail and view on SmartDoctors
     */
    public function getOrderShippingDetail(Request $request)
    {
        try {
            $order = Order::where('order_no', $request->order_id)->select('shipping_address_line', 'shipping_address_line2', 'shipping_city', 'shipping_zipcode', 'shipping_state')->first();
            if (!$order) {
                return response()->json([
                    "message" => 'Order Not Found'
                ], 500);
            }
            return response()->json([
                "order" => $order
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "message" => $e->getMessage()
            ], 500);
        }
    }

    public function updateOrderStatus(Request $request)
    {
        try {
            $order = Order::where('order_no', $request['order_no'])->first();
            $user = User::where('id', $order->user_id)->first();

            if (empty($order)) {
                return response()->json(['message' => "Something went wrong!"], 500);
            }

            $order->update([
                'status' => $request['status'],
                'doctor_response' => $request['doctor_response'],
                'doctor_id' => $request['doctor_id'],
                'doctor_name' => $request['doctor_name'],
                'prescribed_date' => ($request['status'] == 'Prescribed') ? now()->toDateTimeString() : null
            ]);
            $order->save();


            if ($request['status'] === 'Prescribed') {
                $activeOrder = Order::where('user_id', $order->user_id)
                    ->where('is_active', 1)->where('is_changed', true)
                    ->orderBy('created_at', 'DESC')->first();
                if (!empty($activeOrder)) {
                    $activeOrder->update(['is_active' => 0, 'is_changed' => false]);
                }

                $order->update(['is_active' => 1]);
                $order->full_name = $user->first_name . ' ' . $user->last_name;

                //Mail::to($user->email)->send(new OrderPrescribedMail($order));
                $this->sendPrescribedMail($order, $user);
            }

            if ($request['status'] === 'Declined' || $request['status'] === 'Cancelled') {
                $refund = new FinanceController;

                $order->update(['is_active' => 0]);

                $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
                if ($orderRefill) {
                    $orderRefill->update([
                        'refill_status' => 'Cancelled',
                    ]);
                }

                $refundReason = $request['status'] === 'Declined'
                    ? 'Declined by doctor.'
                    : 'Cancelled by User.';

                $refund->refundOrderById($request['order_no'], 1, $refundReason);

                $order->full_name = $user->first_name . ' ' . $user->last_name;

                if ($request['status'] === 'Declined') {
                    $this->sendOrderDeclinedMail($order, $user);
                }

                if ($request['status'] === 'Cancelled') {
                    PaymentController::sendCancelOrderMail($order, $user);
                }

                $activeOrder = Order::where('user_id', $order->user_id)->where('is_active', 1)->orderBy('created_at', 'DESC')->update([
                    'is_changed' => false,
                ]);
            }

            return response()->json(['message' => "Status updated"], 200);

        } catch (\Exception $e) {
            return $e;
        }
    }

    public function NewupdateOrderStatus($request)
    {
        try {
            $order = Order::where('order_no', $request['order_no'])->first();
            $user = User::where('id', $order->user_id)->first();

            if (empty($order)) {
                return response()->json(['message' => "Something went wrong!"], 500);
            }
            $order->update([
                'status' => $request['status'],
                'doctor_response' => $request['doctor_response'],
                'doctor_name' => $request['doctor_name'],
                'prescribed_date' => ($request['status'] == 'Prescribed') ? now()->toDateTimeString() : null
            ]);
            $order->save();


            if ($request['status'] === 'Prescribed') {
                $activeOrder = Order::where('user_id', $order->user_id)
                    ->where('is_active', 1)->where('is_changed', true)
                    ->orderBy('created_at', 'DESC')->first();
                if (!empty($activeOrder)) {
                    $activeOrder->update(['is_active' => 0, 'is_changed' => false]);
                }

                $order->update(['is_active' => 1]);
                $order->full_name = $user->first_name . ' ' . $user->last_name;

                //Mail::to($user->email)->send(new OrderPrescribedMail($order));
                $this->sendPrescribedMail($order, $user);
            }

            if ($request['status'] === 'Declined' || $request['status'] === 'Cancelled') {
                $refund = new FinanceController;

                $order->update(['is_active' => 0]);

                $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
                if ($orderRefill) {
                    $orderRefill->update([
                        'refill_status' => 'Cancelled',
                    ]);
                }

                $refundReason = $request['status'] === 'Declined'
                    ? 'Declined by doctor.'
                    : 'Cancelled by User.';

                $refund->refundOrderById($request['order_no'], 1, $refundReason);

                $order->full_name = $user->first_name . ' ' . $user->last_name;

                if ($request['status'] === 'Declined') {
                    $this->sendOrderDeclinedMail($order, $user);
                }

                if ($request['status'] === 'Cancelled') {
                    PaymentController::sendCancelOrderMail($order, $user);
                }

                $activeOrder = Order::where('user_id', $order->user_id)->where('is_active', 1)->orderBy('created_at', 'DESC')->update([
                    'is_changed' => false,
                ]);
            }

            return true;

        } catch (\Exception $e) {
            return $e;
        }
    }

    private function sendPrescribedMail($order_data, $user)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();
        
        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;
        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'manage_order_action_url' => route('order-details', $order->order_no),
            'product_image_url' => getImage($order->product_image),
            'medicine_variant' => $order->medicine_variant,
            'product_name' => $order->product_name,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_no' => $order->order_no,
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_PRESCRIBED, $emailData, 'order-update@peakscurative.com');
    }

    private function sendOrderDeclinedMail($order_data, $user)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();
        
        $orderRefund = OrderRefundHistory::where('order_no', $order->order_no)
            ->where('refill_number', $order->refill_count)
            ->first();

        if ($orderRefund) {
            $refundAmount = $orderRefund->amount;
        } else {
            $refundAmount = $order->total_price;
        }

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'order_no' => $order->order_no,
            'declined_date' => Carbon::parse($order->updated_at)->format('F j, Y'),
            'product_image_url' => getImage($order->product_image),
            'product_name' => $order->product_name,
            'medicine_variant' => $order->medicine_variant,
            'plan_type' => $order->plan_name,
            'plan_title' => $order->plan_title,
            'order_qty' => $order->product_quantity,
            'strength' => $order->strength,
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'total_refund_amount' => $refundAmount,
            'year' => now()->format('Y'),
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_DECLINED, $emailData, 'order-update@peakscurative.com');
    }

}
