<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Order,OrderRefill};

class PaymentLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order(){
        return $this->hasOne(Order::class, 'order_no', 'order_no');
    }

    public function order_refill(){
        return $this->hasOne(OrderRefill::class, 'order_no', 'order_no');
    }
}
