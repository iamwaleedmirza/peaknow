<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class SubscriptionLogs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $fillable = ['id', 'order_no', 'status', 'updated_by', 'username', 'created_at'];

    public static function store($order_no, $status, $updatedBy)
    {
        try {
            SubscriptionLogs::create([
                'order_no' => $order_no,
                'status' => $status,
                'username' => (Auth::check()) ? Auth::user()->first_name.' '.Auth::user()->last_name.' ('.ucwords(Auth::user()->u_type).')' : '',
                'updated_by' => $updatedBy,
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }
}
