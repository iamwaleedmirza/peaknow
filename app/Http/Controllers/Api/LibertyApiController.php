<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LibertyApiController extends Controller
{
    private $headers;

    public function __construct()
    {
        $this->headers = [
            'Authorization' => 'Basic ' . env('LIBERTY_AUTHORIZATION'),
            'Customer' => env('LIBERTY_CUSTOMER')
        ];
    }

    /**
     * Get Patient ID from Liberty with Phone number.
     * @param $searchQuery
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getPatientId($searchQuery)
    {
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->get('https://api.libertysoftware.com/patient', [
                'search' => $searchQuery
            ]);
    }

    /**
     * Get all Prescriptions for patient with patientId with-in startDate & endDate
     * @param $patientId
     * @param $startDate
     * @param $endDate
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getPatientPrescriptions($patientId, $startDate, $endDate)
    {
        // return Http::withHeaders($this->headers)
        //     ->withoutVerifying()
        //     ->acceptJson()
        //     ->get('https://api.libertysoftware.com/prescriptions', [
        //         'PatientId' => $patientId,
        //         'StartDate' => $startDate,
        //         'EndDate' => $endDate
        //     ]);
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->get('https://api.libertysoftware.com/prescriptions?PatientId='.$patientId.'&StartDate='.$startDate.'&EndDate='.$endDate);
    }

    /**
     * Get details about specific prescription with scriptNumber.
     * @param $scriptNumber
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getPrescriptionDetail($scriptNumber)
    {
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->get("https://api.libertysoftware.com/prescription/${scriptNumber}");
    }

    /**
     * Submit Refill Requests for scripts(array of script objects).
     *
     * @param $scripts @type [ { "scriptNumber": @type int }, { "scriptNumber": @type int } ]
     *
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function submitRefillRequest($scripts)
    {
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->post('https://api.libertysoftware.com/refill', $scripts);
    }

    /**
     * Get Refill status of a Prescription with scriptNumber.
     * @param $scriptNumber
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getRefillStatus($scriptNumber)
    {
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->get("https://api.libertysoftware.com/refill/${scriptNumber}");
    }

    /**
     * Get Shipment Information of a refill linked with a scriptNumber.
     * @param $scriptNumber
     * @param $refillNumber
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function getShipmentInfo($scriptNumber, $refillNumber)
    {
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->get("https://api.libertysoftware.com/shipping/shipment/${scriptNumber}/${refillNumber}");
    }

    /**
     * Update Patient Phone or Shipping address on Liberty.
     * @param $user | @type App/Model/User
     * @param $phone | @type String
     * @param $shippingAddress | @type Array ["Street1": "", "Street2": "", "City": "", "State": "", "Zip": "" ]
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public function updatePatientInfo($user, $phone, $shippingAddress,$updateBio = false)
    {
        $libertyDetails = $user->libertyDetails;
        $patientData = [
            "Id" => $user->liberty_patient_id,
            "BirthDate" => Carbon::parse($libertyDetails->BirthDate)->format('Y-m-d'),
            "Name" => [
                "FirstName" => $libertyDetails->FirstName,
                "LastName" => $libertyDetails->LastName
            ]
        ];
        if ($updateBio == true) {
            $phone = substr($phone, -10);
            $patientData = $patientData + ["Phone" => $phone];
            $patientData = $patientData + ['PhoneType' => 'H'];
            $patientData = $patientData + ["Phone2" => $phone];
            $patientData = $patientData + ['Phone2Type' => 'M'];
            $shippingAddress = ["Street1" => $libertyDetails->Street1, "Street2" => $libertyDetails->Street2, "City" => $libertyDetails->City, "State" => $libertyDetails->State, "Zip" => $libertyDetails->Zip];
        }else{
            $patientData = $patientData + ["Phone" => $libertyDetails->Phone];
            $patientData = $patientData + ['PhoneType' => $libertyDetails->PhoneType];
            $patientData = $patientData + ['Phone2' => $libertyDetails->Phone2];
            $patientData = $patientData + ['Phone2Type' => $libertyDetails->Phone2Type];
        }
        
        if ($shippingAddress) {
            $patientData = $patientData + ["Address" => $shippingAddress];
        }
      
       
        $patientData = $patientData + ['Gender' => $libertyDetails->Gender];
        $patientData = $patientData + ['SSN' => $libertyDetails->SSN];
        $patientData = $patientData + ['DriversLicenseNumber' => $libertyDetails->DriversLicenseNumber];
        $patientData = $patientData + ["Phone" => $libertyDetails->Phone];
        $patientData = $patientData + ['PhoneType' => $libertyDetails->PhoneType];
        $patientData = $patientData + ['Language' => $libertyDetails->Language];
        $patientData = $patientData + ['CustomField1' => $libertyDetails->CustomField1];
        $patientData = $patientData + ['CustomField2' => $libertyDetails->CustomField2];
        $patientData = $patientData + ['CustomField3' => $libertyDetails->CustomField3];
        $patientData = $patientData + ['CustomField4' => $libertyDetails->CustomField4];
        $patientData = $patientData + ['Allergies' => $libertyDetails->Allergies?json_decode($libertyDetails->Allergies,true):null];
        return Http::withHeaders($this->headers)
            ->withoutVerifying()
            ->acceptJson()
            ->put("https://api.libertysoftware.com/patient", $patientData);
    }

}
