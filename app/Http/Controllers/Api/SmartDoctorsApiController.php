<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class SmartDoctorsApiController extends Controller
{
    /**
     * Create Peaks Order on SD database
     * @param array $postData
     * @return string
     */
    // public function createOrder(array $postData)
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);

    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->post(env('SD_API_URL') . 'api/create-peaks-order', $postData);
    //         if ($response->status() != 200) {
    //             Log::debug($response->body());
    //         } else {
    //             return $response->body();
    //         }

    //     } catch (\Exception $e) {
    //         Log::debug($e->getMessage());
    //     }
    // }

    // /**
    //  * Change payment status on peaks_orders table in SD database
    //  * @param $orderId
    //  */
    // public function updatePaymentStatus($orderId)
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);

    //     $postData = [
    //         'order_id' => $orderId,
    //         'payment_status' => 'Paid',
    //     ];

    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->post(env('SD_API_URL') . 'api/change-payment-status', $postData);

    //         if ($response->status() != 200) {
    //             Log::debug($response->body());
    //         }

    //     } catch (\Exception $e) {
    //         Log::debug($e->getMessage());
    //     }
    // }

    // /**
    //  * Getting SmartDoctors Commission in peaks
    //  */
    // public function getTotalSmartDoctorsCommission()
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);

    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->get(env('SD_API_URL') . 'api/getTotalTelemedConsult');

    //         if ($response->status() != 200) {
    //             Log::debug($response->body());
    //         } else {
    //             return $response->body();
    //         }

    //     } catch (\Exception $e) {
    //         Log::debug($e->getMessage());
    //     }
    // }
 
    // /**
    //  * Getting SmartDoctors Commission in peaks
    //  */
    // public function getSmartDoctorsCommission()
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);

    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->get(env('SD_API_URL') . 'api/getTelemedConsult');

    //         if ($response->status() != 200) {
    //             Log::debug($response->body());
    //         } else {
    //             return $response->body();
    //         }

    //     } catch (\Exception $e) {
    //         Log::debug($e->getMessage());
    //     }
    // }

    // /**
    //  * Getting SmartDoctors Commission in peaks
    //  */
    // public function getSmartDoctorsCommissionForOrder($orderId)
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);
    //     $postData = [
    //         'order_id' => $orderId
    //     ];
    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->post(env('SD_API_URL') . 'api/getTelemedConsultForOrder', $postData);

    //         if ($response->status() != 200) {
    //             Log::debug($response->body());
    //         } else {
    //             return $response->body();
    //         }

    //     } catch (\Exception $e) {
    //         Log::debug($e->getMessage());
    //     }
    // }
    // /**
    //  * updating order shipping address on peaks_orders table in SD database
    //  * @param $order
    //  */
    // public function updateShippingAddress($order,$orderID,$type = 'request')
    // {
    //     $site_url = url('/');

    //     $accessToken = array(
    //         'authorization' => env('PEAKS_API_ORDER_TOKEN'),
    //         'site_url' => $site_url
    //     );
    //     $accessToken = json_encode($accessToken);
    //     if ($type == 'request') {
    //         $postData = [
    //             'order_id' => $orderID,
    //             'shipping_address_line' => $order->edit_address_line2 ? $order->edit_address_line.', '.$order->edit_address_line2:$order->edit_address_line,
    //             'shipping_city' => $order->edit_city,
    //             'shipping_zipcode' => $order->edit_zipcode,
    //             'shipping_state' => $order->edit_state
    //         ];
    //     }else{
    //         $postData = [
    //             'order_id' => $orderID,
    //             'shipping_address_line' => $order->address_line2 ? $order->address_line.', '.$order->address_line2:$order->address_line,
    //             'shipping_city' => $order->city,
    //             'shipping_zipcode' => $order->zipcode,
    //             'shipping_state' => $order->state
    //         ];
    //     }
   
    //     try {
    //         $response = Http::withHeaders([
    //             "Accept" => "application/json",
    //             "Authorization" => $accessToken
    //         ])->post(env('SD_API_URL') . 'api/updateOrderShippingAddress', $postData);

    //         if ($response->status() != 200) {
    //             return false;
    //             Log::debug($response->body());
    //         }
    //         return true;
    //     } catch (\Exception $e) {
    //         return false;
    //         Log::debug($e->getMessage());
    //     }
    // }
}
