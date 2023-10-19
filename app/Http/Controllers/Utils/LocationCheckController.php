<?php

namespace App\Http\Controllers\Utils;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LocationCheckController extends Controller
{
    public static function isLocationLive()
    {
        $allowedStates = GeneralSetting::first()->value('allowed_states');
        $allowedStates = explode(',', $allowedStates);

        $userIp = getClientIp();
        $locationData = \Location::get($userIp);
        try {
            if ($locationData->countryName === "Puerto Rico") {
                $currentLocation = $locationData->countryName;
            } else {
                $currentLocation = $locationData->regionName;
            }
            $user = User::where('id', Auth::user()->id)->select('ip_location')->first();
            if ($user->ip_location != $currentLocation) {
                User::where('id', Auth::user()->id)->update(['ip_location' => $currentLocation]);
            }
            return in_array($currentLocation, $allowedStates);

        } catch (\Exception $e) {
        }
    }
}
