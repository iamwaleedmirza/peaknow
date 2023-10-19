<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use App\Http\Controllers\Api\UserDetailsController;

class OrderStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:OrderStatusUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prescribe or Decline order on peaks based on concluded event received from beluga.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        try {
            $orders = \DB::table('orders')
            ->join('beluga_order_details', 'orders.order_no', '=', 'beluga_order_details.order_no')
            ->select('orders.*', 'beluga_order_details.*')
            ->where('orders.status','Pending')
            ->where('beluga_order_details.sent_to_beluga',1)
            ->where('orders.cancellation_request',0)
            ->get();
            foreach ($orders as $order) {
                if($order->is_consult_concluded){
                    if ($order->is_rx_written){
                        $this->updateOrder($order, 'Prescribed');
                    } else {
                        $currentTime = now()->toDateTimeString();
                        $timeToDecline = Carbon::parse($order->consult_concluded_at)->addHours(12)->toDateTimeString();
                        if ($currentTime >= $timeToDecline) {
                            $this->updateOrder($order, 'Declined');
                        }    
                    }
                }
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    private function updateOrder($order, $status)
    {
        $doctorResponse = $status == 'Prescribed' ? 'RX_WRITTEN' : $order->doctor_response;
        // dd($doctorResponse);
        $postData = [
            "order_no" => $order->order_no,
            "status" => $status,
            "doctor_response" => $doctorResponse,
            "doctor_name" => $order->doctor_name
        ];
        $response = (new UserDetailsController())->NewupdateOrderStatus($postData);
        if ($response) {
            \Log::debug("Peaks order #$order->order_no $status.");
        } else {
            \Log::error('Error on Peaks API: Order not updated.');
        }
    }
}
