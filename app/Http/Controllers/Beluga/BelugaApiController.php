<?php

namespace App\Http\Controllers\Beluga;

use App\Http\Controllers\Controller;
// use App\Http\Controllers\Peaks\PeaksCurativeController;
use App\Models\{Order,QuestionAnswer,BelugaLog,User};
use Exception;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Log;

class BelugaApiController extends Controller
{
    public function createVisit($peaksOrderNo)
    {
        try {
            $order = Order::with('belugaOrder')->where('order_no', $peaksOrderNo)->first();
            
            $postData = $this->filterDataForApiCall($order);

            BelugaLog::createLog('PC-' . $order->order_no, 'PeaksCurative', 'Beluga', json_encode($postData));

            if (!$postData) return false;

            $response = Http::post(config('services.beluga.base_url') . '/visit/createNoPayPhotos', $postData);

            BelugaLog::createLog('PC-' . $order->order_no, 'Beluga', 'PeaksCurative', $response->body());

            if ($response->status() == 200) {
                $responseBody = $response->object();
                $visitId = $responseBody->data->visitId;

                $gover_id = getFilePath(User::getDocumentPath($order->user_id, 'govt_id'));
                $response = $this->uploadPhoto($visitId, $gover_id, 'PC-' . $order->order_no);
                if (!$response) return false;

                $order->belugaOrder->update([
                    'visitId' => $visitId,
                    'sent_to_beluga' => true,
                ]);

                return true;
            } else {
                Log::error($response->json());
                return false;
            }

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function filterDataForApiCall($order)
    {
        // $response = (new PeaksCurativeController())->getUserFromPeaks($order->order_no);

        // if ($response->status() != 200) {
        //     return false;
        // }

        // $responseData = $response->object();
        // $user = $responseData->user;
        $user = $order->user;
        $quesAns = QuestionAnswer::where('id', $order->question_ans_id)->value('answers');
        $quesAns = json_decode($quesAns);
        unset($quesAns->{'_token'});
        $quesAns = (array)$quesAns;

        $formObj = [
            'consentsSigned' => true,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'dob' => $user->dob,
            'phone' => substr($user->phone, -10),
            'email' => $user->email,
            'address' => $order->shipping_address_line,
            'city' => $order->shipping_city,
            'state' => $order->shipping_state,
            'zip' => $order->shipping_zipcode,
            'sex' => $user->gender,
            'selfReportedMeds' => ($quesAns['ans_13__1'] == 'Yes') ? $quesAns['ans_13__2'] : 'None',
            'allergies' => ($quesAns['ans_11__1'] == 'Yes') ? $quesAns['ans_11__2'] : 'None',
            'medicalConditions' => ($quesAns['ans_12__1'] == 'Yes') ? $quesAns['ans_12__2'] : 'None',
            'patientPreference' => [
                "name" => strtoupper($order->product_name).' '.strtoupper($order->medicine_variant),
                "strength" => $order->strength.'MG',
                "refills" => '5',
                "quantity" => strval($order->product_quantity),
            ],
        ];

        $ignoreQues = ['que_1', 'que_2', 'que_11__1', 'que_11__2', 'que_12__1', 'que_12__2', 'que_13__1', 'que_13__2'];
        $ignoreAns = ['ans_1', 'ans_2', 'ans_11__1', 'ans_11__2', 'ans_12__1', 'ans_12__2', 'ans_13__1', 'ans_13__2', 'ans_25'];

        if ($quesAns['ans_10__1'] != 'Yes') {
            array_push($ignoreQues, 'que_10__2', 'que_10__3');
            array_push($ignoreAns, 'ans_10__2', 'ans_10__3');
        }

        $questions = [];
        $answers = [];

        foreach ($quesAns as $key => $value) {
            if (preg_match("/^que.*$/", $key)) {
                if (!in_array($key, $ignoreQues)) {
                    $questions[substr($key, 4)] = $value;
                }
            }
            if (preg_match("/^ans.*$/", $key)) {
                if (!in_array($key, $ignoreAns)) {
                    $answers[substr($key, 4)] = $value;
                }
            }
        }

        $formattedQuesAns = [];

        foreach ($questions as $keyQues => $valueQues) {
            foreach ($answers as $keyAns => $valueAns) {
                if ($keyQues == $keyAns) {
                    $formattedQuesAns[$valueQues] = $valueAns;
                }
            }
        }

        $count = 1;
        foreach ($formattedQuesAns as $keyQ => $valueA) {
            if (!empty($valueA)) {
                $formObj['Q' . $count] = $keyQ;
                $formObj['A' . $count] = gettype($valueA) == 'array' ? implode('; ', $valueA) : $valueA;;
                $count++;
            }
        }

        return [
            'formObj' => $formObj,
            'pharmacyId' => '255863',
            'masterId' => 'PC-' . $order->order_no,
            'company' => 'smartDoctors',
            'visitType' => 'ED',
            'apiKey' => config('services.beluga.key'),
        ];
    }

    // private function getPlanStrength($quantity)
    // {
    //     $strength = 'MG';
    //     if ($quantity == 30 || $quantity == 15) {
    //         $strength = '6MG';
    //     } else if ($quantity == 10 || $quantity == 5) {
    //         $strength = '18MG';
    //     }

    //     return $strength;
    // }

    public function uploadPhoto($visitId, $imgUri, $masterId)
    {
        try {
            if(env('APP_ENV')=='local'){
                $imgUri = getFilePath($imgUri);
            }
            $resizedImg = (string)Image::make($imgUri)
                ->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode('jpg', 100)->encode('data-url');
            $base64Data = substr($resizedImg, strpos($resizedImg, ",") + 1);

            $postData = [
                'visitId' => $visitId,
                'image' => [
                    'mime' => 'image/jpeg',
                    'data' => $base64Data,
                ],
                'apiKey' => config('services.beluga.key'),
            ];


            BelugaLog::createLog($masterId, 'PeaksCurative', 'Beluga', json_encode($postData));

            $response = Http::post(config('services.beluga.base_url') . '/external/receivePhoto', $postData);

            BelugaLog::createLog($masterId, 'Beluga', 'PeaksCurative', $response->body());

            if ($response->status() != 200) {
                Log::error($response->status());
                Log::error($response->body());
                return false;
            }

            return true;

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
