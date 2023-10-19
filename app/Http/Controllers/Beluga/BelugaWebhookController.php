<?php

namespace App\Http\Controllers\Beluga;

use App\Models\{BelugaLog,Order,User};
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\Api\SendGridController;
// use App\Http\Controllers\Peaks\PeaksCurativeController;
// use App\SmartDoctor\PeaksOrder;
use Illuminate\Http\Request;
use Log;
use Mail;
use App\Mail\Beluga\CancellationMailSentToBeluga;
use App\Http\Controllers\Beluga\BelugaEvent;

class BelugaWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $masterId = $request['masterId'];
            $event = $request['event'];

            BelugaLog::createLog($masterId, 'Beluga', 'PeaksCurative', json_encode($request->all()), $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $request->getClientIp(), $_SERVER['REMOTE_ADDR']);
            $order = Order::where('order_no', substr($masterId, 3))->first();

            if (empty($order)) {
                $responseData = ['message' => 'Visit with provided masterId does not exist'];

                BelugaLog::createLog($masterId, 'PeaksCurative', 'Beluga', json_encode($responseData));

                return response()->json($responseData, 400);
            }

            $order->update([
                'doctor_response' => $event
            ]);

            $order->belugaOrder->update([
                'beluga_ip' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $request->getClientIp(),
                'beluga_event_endpoint' => $_SERVER['REMOTE_ADDR'] ?? NULL,
            ]);
            if($order->cancellation_request==1 && ($event == BelugaEvent::RX_WRITTEN || $event == BelugaEvent::CONSULT_CONCLUDED)){
                return $this->refundOrder($order);
            } else {
                if ($event == BelugaEvent::CONSULT_CANCELED) {
                    return $this->cancelOrder($order);

                } elseif ($event == BelugaEvent::CONSULT_CONCLUDED) {
                    return $this->handleConcludedEvent($order);

                } elseif ($event == BelugaEvent::RX_WRITTEN) {
                    $doctorName = $request->data['docFirstName'] . ' ' . $request->data['docLastName'];
                    return $this->handleRxEvent($order, $doctorName);

                } else {
                    Log::debug($event);

                    $responseData = ['message' => 'Event not recognized'];

                    BelugaLog::createLog($masterId, 'PeaksCurative', 'Beluga', json_encode($responseData));

                    return response()->json($responseData, 400);
                }
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            $responseData = ['message' => 'Internal server error'];

            BelugaLog::createLog($masterId, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData, 500);
        }
    }

    private function cancelOrder($order)
    {
        try {
            $user = User::where('id', $order->user_id)->first();
            if ($order->status == 'Cancelled') {
                $responseData = ['message' => 'masterId already cancelled'];

                BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

                return response()->json($responseData, 400);
            }

            

            $order->update(['is_active' => 0,'cancellation_request' => 0,'status' => 'Cancelled']);

            $orderRefill = $order->orderRefill()->where('refill_number', 0)->first();
            if ($orderRefill) {
                $orderRefill->update([
                    'refill_status' => 'Cancelled',
                ]);
            }

            if($order->is_refunded==0){
                $refundReason = 'Cancelled by User.';
                $refund = new FinanceController;
                $refund->refundOrderById($order->order_no, 1, $refundReason);    
            }

            $order->full_name = $order->user->first_name.' '.$order->user->last_name;

            PaymentController::sendCancelOrderMail($order, $user);

            $activeOrder = Order::where('user_id', $order->user_id)->where('is_active', 1)->orderBy('created_at', 'DESC')->update([
                'is_changed' => false,
            ]);

            $responseData = ['message' => 'Success'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            $responseData = ['message' => 'Internal server error'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData, 500);
        }
    }

    private function refundOrder($order)
    {
        try {
            $user = User::where('id', $order->user_id)->first();
            if ($order->status == 'Cancelled') {
                $responseData = ['message' => 'masterId already cancelled'];

                BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

                return response()->json($responseData, 400);
            }

            $refund = new FinanceController;

            $order->update(['is_active' => 0]);

            if($order->is_refunded==0){
                $refundReason = 'Cancelled by User.';
                $refund = new FinanceController;
                $refund->refundOrderById($order->order_no, 1, $refundReason);    
            }

            $order->full_name = $order->user->first_name.' '.$order->user->last_name;

            PaymentController::sendCancelOrderMail($order, $user);

            $activeOrder = Order::where('user_id', $order->user_id)->where('is_active', 1)->orderBy('created_at', 'DESC')->update([
                'is_changed' => false,
            ]);

            $responseData = ['message' => 'Success'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error($e->getMessage());

            $responseData = ['message' => 'Internal server error'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData, 500);
        }
    }

    private function handleConcludedEvent($order)
    {
        if ($order->belugaOrder->is_consult_concluded) {
            $responseData = ['message' => 'masterId already concluded'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData, 400);
        }

        $order->belugaOrder->update([
            'is_consult_concluded' => true,
            'consult_concluded_at' => now()->toDateTimeString(),
        ]);

        $responseData = ['message' => 'Success'];

        BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

        return response()->json($responseData);
    }

    private function handleRxEvent($order, $doctorName)
    {
        if ($order->belugaOrder->is_rx_written) {
            $responseData = ['message' => 'masterId already prescribed'];

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

            return response()->json($responseData, 400);
        }

        $order->update([
            'doctor_name' => $doctorName
        ]);

        $order->belugaOrder->update([
            'is_rx_written' => true,
            'rx_written_at' => now()->toDateTimeString(),
        ]);

        $responseData = ['message' => 'Success'];

        BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($responseData));

        return response()->json($responseData);
    }
}
