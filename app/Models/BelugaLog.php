<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BelugaLog extends Model
{
    protected $guarded = ['id'];

    public static function createLog($masterId, $sentFrom, $sentTo, $response, $ip = null, $endpoint = null)
    {
        try {
            BelugaLog::create([
                'master_id' => $masterId,
                'request_sent_from' => $sentFrom,
                'request_sent_to' => $sentTo,
                'event_response' => $response,
                'ip_address' => $ip,
                'endpoint_url' => $endpoint,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
