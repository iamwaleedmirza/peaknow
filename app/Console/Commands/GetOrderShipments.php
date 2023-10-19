<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Api\SendGridController;
use App\Http\Controllers\Email\EmailController;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderRefill;
use App\Models\OrderShipment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GetOrderShipments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetOrderShipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get details of shipped orders.';

    /**
     * @var LibertyApiController
     */
    private $libertyAPI;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->libertyAPI = new LibertyApiController();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // $this->fetchOrderShipments();

        // $this->checkForShippedOrders();

        $this->createShipment();
    }

    private function createShipment(){
        $orderRefills = OrderRefill::where('is_shipped', false)->where('refill_status','Confirmed')->get();
        foreach ($orderRefills as $refill) {

            $order = Order::where('order_no', $refill->order_no)->first();

            $response = $this->libertyAPI->getShipmentInfo($order->script_number, $refill->refill_number);
            if ($response->ok()) {
                $jsonResponse = $response->object();

                if(@$jsonResponse->ShipDate && @$jsonResponse->TrackingNumber){
                    $orderShipment = OrderShipment::where('order_no', $order->order_no)
                        ->where('refill_number', $refill->refill_number)
                        ->first();

                    if ($orderShipment == null) {
                        $orderShipment = OrderShipment::create([
                            'order_no' => $order->order_no,
                            'refill_number' => $order->refill_count,
                            'ship_date' => $jsonResponse->ShipDate,
                            'ship_type' => $jsonResponse->ShipType,
                            'tracking_number' => $jsonResponse->TrackingNumber,
                        ]);

                        $refill->update([
                            'is_shipped' => true,
                            'refill_status' => 'Completed'
                        ]);

                        $user = $order->user;

                        if ($refill->refill_number == 0) {
                            $this->sendShippingConfirmationMail($order, $user, $orderShipment->tracking_number, $orderShipment->ship_date);
                        } else {
                            $emailManager = new EmailController();
                            $emailManager->sendRefillShippingConfirmation($order, $refill, $orderShipment->tracking_number, $orderShipment->ship_date);
                        }
                    }
                }
            }
        }

    }

    // private function fetchOrderShipments()
    // {
    //     $orders = Order::where('status', 'Prescribed')
    //         ->where('script_number', '!=', null)
    //         ->where('refill_count', '!=', null)
    //         ->get();

    //     foreach ($orders as $order) {
    //         $orderRefill = OrderRefill::where('order_no', $order->order_no)
    //             ->where('refill_number', $order->refill_count)->first();

    //         if ($orderRefill && $orderRefill->is_shipped) {
    //             continue;
    //         }

    //         $response = $this->libertyAPI->getShipmentInfo($order->script_number, $order->refill_count);

    //         if ($response->ok()) {
    //             $jsonResponse = $response->object();

    //             $orderShipment = OrderShipment::where('order_no', $order->order_no)
    //                 ->where('refill_number', $order->refill_count)
    //                 ->first();

    //             if ($orderShipment == null) {
    //                 $orderShipment = OrderShipment::create([
    //                     'order_no' => $order->order_no,
    //                     'refill_number' => $order->refill_count,
    //                     'ship_date' => $jsonResponse->ShipDate,
    //                     'ship_type' => $jsonResponse->ShipType,
    //                     'tracking_number' => $jsonResponse->TrackingNumber,
    //                 ]);

    //                 // $orderShipment->getOrderRefill()->update(['refill_status' => 'Confirmed']);
    //             }
    //         }

    //     }
    // }

    // private function checkForShippedOrders()
    // {
    //     $orderRefills = OrderRefill::where('is_shipped', false)->get();

    //     foreach ($orderRefills as $refill) {
    //         $orderShipment = $refill->refillShipment();

    //         if ($orderShipment) {
    //             if (strtotime(date('Y-m-d')) >= strtotime($orderShipment->ship_date)) {
    //                 $refill->update([
    //                     'is_shipped' => true,
    //                     'refill_status' => 'Completed'
    //                 ]);

    //                 $order = Order::where('order_no', $refill->order_no)->first();

    //                 $user = $order->user;
    //                 if ($refill->refill_number == 0) {
    //                     $this->sendShippingConfirmationMail($order, $user, $orderShipment->tracking_number, $orderShipment->ship_date);
    //                 } else {
    //                     $emailManager = new EmailController();
    //                     $emailManager->sendRefillShippingConfirmation($order, $refill, $orderShipment->tracking_number, $orderShipment->ship_date);
    //                 }

    //             }
    //         }

    //     }
    // }

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
            'order_date' => Carbon::parse($order->created_at)->format('F j, Y'),
            'shipped_date' => Carbon::parse($shipDate)->format('F j, Y'),
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_ORDER_SHIPPING_CONFIRMATION, $emailData, 'order-update@peakscurative.com');
    }
}
