<?php

use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

function getImage($image_path)
{
    $url = "";
    $env = config('app.env');
    if (!empty($image_path)) {
        if ($env == 'local' && Storage::disk('local')->exists($image_path)) {
            $split_file_name = explode('/', $image_path);
            $url = URL::temporarySignedRoute(
                'images_route',
                now()->addMinutes(30),
                ['folder' => $split_file_name[0], 'filename' => $split_file_name[1]]
            );
        } else if ($env != 'local' && Storage::disk('s3')->exists($image_path)) {
            $url = 'https://assets.peakscurative.com/' . $image_path;
        }
    }
    return $url;
}

function getFilePath($path)
{
    $filePath = null;

    if (!empty($path)) {
        $env = config('app.env');
        if ($env === 'local' && Storage::disk('local')->exists($path)) {
            $filePath = Storage::disk('local')->path($path);
        } else if ($env !== 'local' && Storage::disk('s3')->exists($path)) {
            $filePath = 'https://assets.peakscurative.com/' . $path;
        }
    }

    return $filePath;
}

function getClientIp()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = file_get_contents("http://ipecho.net/plain");
    if ($ipaddress == '127.0.0.1') {
        $ipaddress = file_get_contents("http://ipecho.net/plain");
    }
    if (app()->environment(['production', 'staging'])) {
        $ipaddress = explode(',', $ipaddress);
        return $ipaddress[0];
    } else {
        return $ipaddress;
    }
}

function getActiveStates()
{
    $liveStates = GeneralSetting::first()->allowed_states;

    $liveStatesArr = explode(',', $liveStates);

    $activeStates = [];

    $states = \DB::table('states')->get();

    foreach ($states as $key => $state) {
        if (in_array($state->state, $liveStatesArr)) {
            array_push($activeStates, $state);
        }
    }

    return $activeStates;
}

function getPermission()
{
    $data = array(
        ["id" => 1,"name"=>"View Permissions List"],
        ["id" => 2,"name"=>"Add and Update Permissions List"],
        ["id" => 3,"name"=>"Delete Permissions List"],
    );
    return $data;
}


function base64url_encode($qty) {

  return rtrim(strtr(base64_encode($qty), '+/', '-_'), '=');

}