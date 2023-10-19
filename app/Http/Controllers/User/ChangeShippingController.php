<?php

namespace App\Http\Controllers\User;

use Exception;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Api\SmartDoctorsApiController;

class ChangeShippingController extends Controller
{
    /**
     * updating order shipping address 
     * @param $request
     */
    public function updateOrderShippingAddress(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric',
            'address_type' => 'required|string',
        ]);
        if ($request->address_type == 'edit') {
            $request->validate([
                'edit_address_line' => 'required|max:50|regex:/^[a-zA-Z0-9",.\/\s,\'-]*$/',
                'edit_city' => 'required|regex:/^[A-Za-z ]+$/',
                'edit_zipcode' => 'required|postal_code:US',
                'edit_state' => 'required'
            ],['edit_address_line.required'=>'The street 1 field is required.','edit_city.required'=>'The city field is required.','edit_zipcode.required'=>'The zipcode field is required.','edit_state.required'=>'The state field is required.']);
        } else {
            $request->validate([
                'shipping_address' => 'required|numeric',
            ]);
        }

        $order = Order::find($request->order_id);
        // if ($order->status == 'Pending') {
        //     return $this->updatePendingOrderShippingAddress($order, $request);
        // }
        if ($order->status == 'Prescribed') {
            return $this->updatePrescribedOrderShippingAddress($order, $request);
        }
    }

    /**
     * Getting pending order and updating shipping address 
     * @param $order
     */
    // public function updatePendingOrderShippingAddress($order, $request)
    // {
    //     $sdAPI = new SmartDoctorsApiController();
    //     try {
    //         if ($request->address_type == 'edit') {
    //             $response = $sdAPI->updateShippingAddress($request,$order->id);
    //             if ($response) {
    //             $order->update([
    //                 'shipping_address_line' => $request->edit_address_line,
    //                 'shipping_address_line2' => $request->edit_address_line2,
    //                 'shipping_city' => $request->edit_city,
    //                 'shipping_zipcode' => $request->edit_zipcode,
    //                 'shipping_state' => $request->edit_state,
    //             ]);
    //             $order->orderRefill()->where('refill_number', 0)->first()->update([
    //                 'shipping_address_line' => $request->edit_address_line,
    //                 'shipping_address_line2' => $request->edit_address_line2,
    //                 'shipping_city' => $request->edit_city,
    //                 'shipping_zipcode' => $request->edit_zipcode,
    //                 'shipping_state' => $request->edit_state,
    //             ]);
    //             return Response::json(array(
    //                 'success' => true,
    //                 'orderShippingAddress' => [$order->shipping_address_line . ' ' . $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zipcode],
    //                 'message' => ['Order shipping address has been updated successfully']
    
    //             ), 200);
    //         } else {
    //             return Response::json(array(
    //                 'success' => false,
    //                 'error_title' => Error_1003_Title,
    //                 'error_description' => Error_1003_Description,

    //             ), 400);
    //         }
    //         } else {
    //             $userAddress = UserAddress::find($request->shipping_address);
    //             $response = $sdAPI->updateShippingAddress($userAddress,$order->id,'userAddress');
    //             if ($response) {
    //             $order->update([
    //                 'shipping_address_line' => $userAddress->address_line,
    //                 'shipping_address_line2' => $userAddress->address_line2,
    //                 'shipping_city' => $userAddress->city,
    //                 'shipping_zipcode' => $userAddress->zipcode,
    //                 'shipping_state' => $userAddress->state,
    //             ]);
    //             $order->orderRefill()->where('refill_number', 0)->first()->update([
    //                 'shipping_address_line' => $userAddress->address_line,
    //                 'shipping_address_line2' => $userAddress->address_line2,
    //                 'shipping_city' => $userAddress->city,
    //                 'shipping_zipcode' => $userAddress->zipcode,
    //                 'shipping_state' => $userAddress->state,
    //             ]);
    //             return Response::json(array(
    //                 'success' => true,
    //                 'orderShippingAddress' => [$order->shipping_address_line . ' ' . $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zipcode],
    //                 'message' => ['Order shipping address has been updated successfully']
    
    //             ), 200);
    //             } else {
    //                 return Response::json(array(
    //                     'success' => false,
    //                     'error_title' => Error_1003_Title,
    //                     'error_description' => Error_1003_Description,
    
    //                 ), 400);
    //             }
    //         }

            
    //     } catch (Exception $e) {
    //         return Response::json(array(
    //             'success' => false,
    //             'errors' => [$e->getMessage()]

    //         ), 400);
    //     }
    // }
    /**
     * Getting prescribed order and updating shipping address 
     * @param $order
     */
    public function updatePrescribedOrderShippingAddress($order, $request)
    {
        $libertyAPI = new LibertyApiController();
        $user = Auth::user();
        if (!$user->liberty_patient_id) {
            return Response::json(array(
                'success' => false,
                'errors' => ['Something went wrong! Please try again later.']

            ), 400);
        }
        try {
            if ($request->address_type == 'edit') {
                $response = $libertyAPI->updatePatientInfo($order->user, '', ["Street1" => $request->edit_address_line, "Street2" => $request->edit_address_line2, "City" => $request->edit_city, "State" => $request->edit_state, "Zip" => $request->edit_zipcode]);
                if ($response->status() == 200) {
                    $order->update([
                        'shipping_address_line' => $request->edit_address_line,
                        'shipping_address_line2' => $request->edit_address_line2,
                        'shipping_city' => $request->edit_city,
                        'shipping_zipcode' => $request->edit_zipcode,
                        'shipping_state' => $request->edit_state
                    ]);
                    $order->user->libertyDetails->update([
                        'Street1' => $request->edit_address_line,
                        'Street2' => $request->edit_address_line2,
                        'City' => $request->edit_city,
                        'State' => $request->edit_state,
                        'Zip' => $request->edit_zipcode
                    ]);
                } else {
                    return Response::json(array(
                        'success' => false,
                        'error_title' => Error_3002_Title,
                        'error_description' => Error_3002_Description,

                    ), 400);
                }
            } else {
                $userAddress = UserAddress::find($request->shipping_address);
                // if (strlen($userAddress->address_line) >= 50) {
                //     return Response::json(array(
                //         'success' => false,
                //         'errors' => ['Shipping address need to be below 50 characters!!']

                //     ), 400);
                // }
                $response = $libertyAPI->updatePatientInfo($order->user, '', ["Street1" => $userAddress->address_line, "Street2" => $userAddress->address_line2, "City" => $userAddress->city, "State" => $userAddress->state, "Zip" => $userAddress->zipcode]);
                if ($response->status() == 200) {
                    $order->update([
                        'shipping_address_line' => $userAddress->address_line,
                        'shipping_address_line2' => $userAddress->address_line2,
                        'shipping_city' => $userAddress->city,
                        'shipping_zipcode' => $userAddress->zipcode,
                        'shipping_state' => $userAddress->state
                    ]);
                    $order->user->libertyDetails->update([
                        'Street1' => $userAddress->address_line,
                        'Street2' => $userAddress->address_line2,
                        'City' => $userAddress->city,
                        'State' => $userAddress->state,
                        'Zip' => $userAddress->zipcode
                    ]);
                } else {
                    return Response::json(array(
                        'success' => false,
                        'error_title' => Error_3002_Title,
                        'error_description' => Error_3002_Description,

                    ), 400);
                }
            }

            return Response::json(array(
                'success' => true,
                'orderShippingAddress' => [$order->shipping_address_line . ' ' . $order->shipping_city . ', ' . $order->shipping_state . ' - ' . $order->shipping_zipcode],
                'message' => ['Order shipping address has been updated successfully']

            ), 200);
        } catch (\Exception $e) {
            return Response::json(array(
                'success' => false,
                'errors' => [$e->getMessage()]

            ), 400);
        }
    }
}
