<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\LibertyApiController;
use App\Http\Controllers\Api\SendGridController;
use App\Models\Order;
use App\Models\OrderRefill;
use App\Models\OrderSubscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetPrescriptionsByLibertyScriptNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetPrescriptionsByLibertyScriptNumber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get prescriptions by liberty script number. This cron job run when admin update manual liberty script number from admin panel';

    /**
     * The libertyAPI object.
     *
     * @var object
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
     */
    public function handle()
    {
        $orders = DB::table('orders as o')->where('status', 'Prescribed')
            ->where('script_number','!=', null)
            ->where('is_script_entered_by_admin',1)
            ->where('o.status','Prescribed')
            ->where('orf.refill_status','Pending')
            ->join('users as u', 'user_id', '=', 'u.id')
            ->join('order_refills as orf', 'orf.order_no', '=', 'o.order_no')
            ->select(['u.phone', 'u.liberty_patient_id', 'o.id', 'o.user_id', 'o.script_number', 'o.product_price',
                'o.prescribed_date', 'o.order_no', 'o.product_quantity', 'o.product_ndc', 'o.product_ndc_2',
                'o.is_subscription','o.created_at'])
            ->get();

        foreach ($orders as $order) {
            $startDate = Carbon::parse($order->created_at)->format('Y-m-d');
            $endDate = Carbon::now()->format('Y-m-d');

            if (empty($order->liberty_patient_id)) {
                $phone = substr($order->phone, -10);
                $response = $this->libertyAPI->getPatientId($phone);
                if ($response->ok()) {
                    $jsonResponse = $response->json();

                    if (!empty($jsonResponse) && isset($jsonResponse[0])) {

                        $libertyPatientId = $jsonResponse[0]['Id'];
                        $libertyPatientDetails = $jsonResponse[0];

                        $user = User::where('id', $order->user_id);
                        $user->update([
                            'liberty_patient_id' => $libertyPatientId
                        ]);
                        if (!$user->first()->libertyDetails) {
                            $user->first()->libertyDetails()->create([
                                'PatientId' => $libertyPatientDetails['Id'],
                                'ExternalId' => $libertyPatientDetails['ExternalId'],
                                'AccountNumber' => $libertyPatientDetails['AccountNumber'],
                                'ChargeCode' => $libertyPatientDetails['ChargeCode'],
                                'FirstName' => $libertyPatientDetails['Name']['FirstName'],
                                'MiddleInitial' => $libertyPatientDetails['Name']['MiddleInitial'],
                                'LastName' => $libertyPatientDetails['Name']['LastName'],
                                'BirthDate' => Carbon::parse($libertyPatientDetails['BirthDate'])->format('Y-m-d'),
                                'Street1' => $libertyPatientDetails['Address']['Street1'],
                                'Street2' => $libertyPatientDetails['Address']['Street2'],
                                'City' => $libertyPatientDetails['Address']['City'],
                                'State' => $libertyPatientDetails['Address']['State'],
                                'Zip' => $libertyPatientDetails['Address']['Zip'],
                                'Gender' => $libertyPatientDetails['Gender'],
                                'SSN' => $libertyPatientDetails['SSN'],
                                'DriversLicenseNumber' => $libertyPatientDetails['DriversLicenseNumber'],
                                'Phone' => $libertyPatientDetails['Phone'],
                                'PhoneType' => $libertyPatientDetails['PhoneType'],
                                'Phone2' => $libertyPatientDetails['Phone2'],
                                'Phone2Type' => $libertyPatientDetails['Phone2Type'],
                                'Email' => $libertyPatientDetails['Email'],
                                'Language' => $libertyPatientDetails['Language'],
                                'CustomField1' => $libertyPatientDetails['CustomField1'],
                                'CustomField2' => $libertyPatientDetails['CustomField2'],
                                'CustomField3' => $libertyPatientDetails['CustomField3'],
                                'CustomField4' => $libertyPatientDetails['CustomField4'],
                                'Allergies' => $libertyPatientDetails['Allergies'] ? json_encode($libertyPatientDetails['Allergies'], true) : null,
                            ]);
                        }

                        $prescriptionsResponse = $this->libertyAPI->getPrescriptionDetail($order->script_number);
                        $this->saveScriptNumber($order, $prescriptionsResponse);
                    }
                }

            } else {
                $prescriptionsResponse = $this->libertyAPI->getPrescriptionDetail($order->script_number);
                $this->saveScriptNumber($order, $prescriptionsResponse);
            }
        }
    }

    public function saveScriptNumber($order, $response)
    {
        if ($response->ok()) {
            $responseBody = $response->object();
            
            $scriptItem = $responseBody;

            $productNdc = (int)$order->product_ndc;

            if (!empty($scriptItem)) {
                if ( (float)$order->product_quantity === (float)$scriptItem->FullDispenseQuantity && $productNdc === (int)$scriptItem->DrugPrescribed->NDC) {
                    if(date('Y-m-d',strtotime($order->created_at))<=$scriptItem->WrittenDate && $scriptItem->LastRefillNumber==0){
                        
                        Order::where('id', $order->id)->update([
                            'script_number' => $scriptItem->ScriptNumber,
                            'prescription_written_date' => $scriptItem->WrittenDate,
                            'refill_until_date' => $scriptItem->RefillUntilDate
                        ]);

                        if ($order->is_subscription) {
                            $subscription = OrderSubscription::where('order_no', $order->order_no)->first();
                            $subscription->update([
                                'next_refill_date' => Carbon::parse($scriptItem->WrittenDate)->addDays(27)->toDateString()
                            ]);
                        }

                        $updatedOrder = Order::where('id', $order->id)->first();
                        if ($updatedOrder->is_subscription == true) {
                            $this->sendOrderSubscriptionMail($updatedOrder);
                        }
                        $orderRefill = OrderRefill::where('order_no', $order->order_no)->where('refill_number', 0)->first();
                        if ($orderRefill) {
                            $orderRefill->update([
                                'refill_status' => 'Confirmed',
                                'refill_date' => $scriptItem->Fills[0]->DispenseDate
                            ]);
                        } else {
                            \Log::debug('Order Refill-0 not found: '.$order->order_no);
                        }
                        // else {
                        //     OrderRefill::create([
                        //         'refill_status' => 'Confirmed',
                        //         'refill_number' => 0,
                        //         'refill_date' => $scriptItem->WrittenDate,
                        //         'order_no' => $order->order_no
                        //     ]);
                        // }
                    }
                }
            }

        }
    }

    private function sendOrderSubscriptionMail($order_data)
    {
        $order = Order::where('order_no',$order_data->order_no)->first();
        $user = User::select('first_name','last_name','email')->where('id',$order->user_id)->first();

        $orderRefill = OrderRefill::where('order_no', $order->order_no)->where('refill_number', 0)->first();

        $address = $order->shipping_address_line2 ? $order->shipping_address_line . ',<br>' . $order->shipping_address_line2 : $order->shipping_address_line;

        $emailData = [
            'account_username' => $user->first_name . ' ' . $user->last_name,
            'start_date' => Carbon::parse($order->subscription->subscription_startDate)->format('F j, Y'),
            'manage_plan_action_url' => route('user.plan.index'),
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
            'total_price' => $order->total_price,
            'shipping_address' => $order->shipping_fullname . '<br>' . $address . ',<br>' . $order->shipping_city . ' - ' . $order->shipping_zipcode . ', ' . $order->shipping_state,
            'year' => now()->format('Y')
        ];
        $mailResponse = SendGridController::sendMail($user->email, TEMPLATE_ID_SUBSCRIPTION_ACTIVATION, $emailData, 'subscription-confirm@peakscurative.com');
    }
}
